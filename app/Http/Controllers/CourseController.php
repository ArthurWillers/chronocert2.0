<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Auth::user()
            ->courses()
            ->withCount('categories')
            ->latest()
            ->get();

        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        DB::transaction(function () use ($request) {
            $course = Auth::user()->courses()->create([
                'name' => $request->name,
                'total_hours' => $request->total_hours,
            ]);

            foreach ($request->categories as $category) {
                $course->categories()->create([
                    'name' => $category['name'],
                    'max_hours' => $category['max_hours'],
                ]);
            }
        });

        return redirect()->route('courses.index')->with('toast', [
            'type' => 'success',
            'message' => 'Curso criado com sucesso!',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);

        $course->load('categories');

        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $this->authorize('update', $course);

        DB::transaction(function () use ($request, $course) {
            $course->update([
                'name' => $request->name,
                'total_hours' => $request->total_hours,
            ]);

            $keepIds = collect($request->categories)
                ->pluck('id')
                ->filter()
                ->all();

            $course->categories()->whereNotIn('id', $keepIds)->delete();

            foreach ($request->categories as $categoryData) {
                if (! empty($categoryData['id'])) {
                    $course->categories()
                        ->where('id', $categoryData['id'])
                        ->update([
                            'name' => $categoryData['name'],
                            'max_hours' => $categoryData['max_hours'],
                        ]);
                } else {
                    $course->categories()->create([
                        'name' => $categoryData['name'],
                        'max_hours' => $categoryData['max_hours'],
                    ]);
                }
            }
        });

        return redirect()->route('courses.index')->with('toast', [
            'type' => 'success',
            'message' => 'Curso atualizado com sucesso!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        $course->delete();

        return redirect()->route('courses.index')->with('toast', [
            'type' => 'success',
            'message' => 'Curso excluído com sucesso!',
        ]);
    }
}
