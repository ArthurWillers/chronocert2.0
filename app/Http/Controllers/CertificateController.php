<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCertificateRequest;
use App\Http\Requests\UpdateCertificateRequest;
use App\Models\Category;
use App\Models\Certificate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use ZipArchive;

class CertificateController extends Controller
{
    use AuthorizesRequests;

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('create', Certificate::class);

        $courses = Auth::user()->courses()->with('categories')->get();

        $selectedCourseId = $request->query('course_id');
        $selectedCategoryId = $request->query('category_id');

        return view('certificates.create', compact('courses', 'selectedCourseId', 'selectedCategoryId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCertificateRequest $request)
    {
        $this->authorize('create', Certificate::class);

        $category = Category::with('course')->findOrFail($request->category_id);

        // Verify that the category belongs to a course owned by the user
        if ($category->course->user_id !== Auth::id()) {
            abort(403);
        }

        $certificate = Auth::user()->certificates()->create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'hours' => $request->hours,
        ]);

        if ($request->hasFile('file')) {
            $certificate
                ->addMediaFromRequest('file')
                ->usingFileName($this->formatFileName($certificate, $request->file('file')))
                ->toMediaCollection('certificate_file');
        }

        return redirect()->route('dashboard', $category->course->id)->with('toast', [
            'type' => 'success',
            'message' => 'Certificado cadastrado com sucesso!',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Certificate $certificate)
    {
        $this->authorize('update', $certificate);

        $certificate->load(['category.course', 'media']);
        $courses = Auth::user()->courses()->with('categories')->get();

        return view('certificates.edit', compact('certificate', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCertificateRequest $request, Certificate $certificate)
    {
        $this->authorize('update', $certificate);

        $category = Category::with('course')->findOrFail($request->category_id);
        if ($category->course->user_id !== Auth::id()) {
            abort(403);
        }

        $certificate->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'hours' => $request->hours,
        ]);

        if ($request->hasFile('file')) {
            $certificate
                ->addMediaFromRequest('file')
                ->usingFileName($this->formatFileName($certificate, $request->file('file')))
                ->toMediaCollection('certificate_file');
        }

        $courseId = $category->course->id;

        return redirect()->route('dashboard', $courseId)->with('toast', [
            'type' => 'success',
            'message' => 'Certificado atualizado com sucesso!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        $this->authorize('delete', $certificate);

        $certificate->load('category.course');
        $courseId = $certificate->category->course->id;

        $certificate->delete();

        return redirect()->route('dashboard', $courseId)->with('toast', [
            'type' => 'success',
            'message' => 'Certificado excluído com sucesso!',
        ]);
    }

    /**
     * Download the certificate file.
     */
    public function download(Certificate $certificate)
    {
        $this->authorize('view', $certificate);

        $media = $certificate->getFirstMedia('certificate_file');

        if (! $media) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Este certificado não possui arquivo anexado.',
            ]);
        }

        return $media;
    }

    /**
     * Bulk download certificates as a ZIP file.
     */
    public function bulkDownload(Request $request)
    {
        $request->validate([
            'certificates' => ['required', 'array', 'min:1'],
            'certificates.*' => ['integer'],
        ]);

        $certificates = Certificate::whereIn('id', $request->certificates)
            ->where('user_id', Auth::id())
            ->with('media', 'category.course')
            ->get();

        if ($certificates->isEmpty()) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Nenhum certificado encontrado.',
            ]);
        }

        $zipFileName = 'certificados_'.now()->format('Y-m-d_His').'.zip';
        $zipPath = storage_path('app/temp/'.$zipFileName);

        // Ensure temp directory exists
        if (! is_dir(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Erro ao criar o arquivo ZIP.',
            ]);
        }

        $usedNames = [];

        foreach ($certificates as $certificate) {
            $media = $certificate->getFirstMedia('certificate_file');
            if (! $media) {
                continue;
            }

            $courseName = Str::slug($certificate->category->course->name ?? 'sem-curso');
            $categoryName = Str::slug($certificate->category->name ?? 'sem-categoria');
            $certTitle = Str::slug($certificate->title);
            $extension = pathinfo($media->file_name, PATHINFO_EXTENSION);

            $entryName = "{$courseName}/{$categoryName}/{$certTitle}.{$extension}";

            // Avoid duplicate names
            $counter = 1;
            $originalEntry = $entryName;
            while (in_array($entryName, $usedNames)) {
                $entryName = str_replace(".{$extension}", "_{$counter}.{$extension}", $originalEntry);
                $counter++;
            }
            $usedNames[] = $entryName;

            $zip->addFile($media->getPath(), $entryName);
        }

        $zip->close();

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Format file name for storage: curso_categoria_titulo.ext
     */
    private function formatFileName(Certificate $certificate, $file): string
    {
        $certificate->load('category.course');

        $courseName = Str::slug($certificate->category->course->name ?? 'sem-curso');
        $categoryName = Str::slug($certificate->category->name ?? 'sem-categoria');
        $title = Str::slug($certificate->title);
        $extension = $file->getClientOriginalExtension();

        return "{$courseName}_{$categoryName}_{$title}.{$extension}";
    }
}
