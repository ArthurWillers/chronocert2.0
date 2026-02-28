<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $courses = $user->courses()->with('categories.certificates')->get();

        $activeCourse = null;
        if ($courses->isNotEmpty()) {
            $activeCourseId = $request->input('course', $courses->first()->id);
            $activeCourse = $courses->firstWhere('id', $activeCourseId) ?? $courses->first();
        }

        $stats = [
            'completed_hours' => 0,
            'progress_percentage' => 0,
            'categories_count' => 0,
            'certificates_count' => 0,
        ];

        if ($activeCourse) {
            $completedHours = $activeCourse->categories
                ->flatMap->certificates
                ->sum('hours');

            $stats = [
                'completed_hours' => $completedHours,
                'progress_percentage' => $activeCourse->total_hours > 0
                    ? min(100, round(($completedHours / $activeCourse->total_hours) * 100, 1))
                    : 0,
                'categories_count' => $activeCourse->categories->count(),
                'certificates_count' => $activeCourse->categories->flatMap->certificates->count(),
            ];
        }

        return view('dashboard', compact('courses', 'activeCourse', 'stats'));
    }
}
