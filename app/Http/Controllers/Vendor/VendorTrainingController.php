<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorCourseProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VendorTrainingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display training courses index
     */
    public function index()
    {
        $user = Auth::user();
        $courses = config('training.courses', []);
        
        // Get progress for each course
        $coursesWithProgress = [];
        foreach ($courses as $course) {
            $progress = $user->getCourseProgress($course['number']);
            $coursesWithProgress[] = [
                'course' => $course,
                'progress' => $progress,
                'isCompleted' => $progress ? $progress->isCompleted() : false,
                'isLocked' => $this->isCourseLocked($user, $course),
            ];
        }

        return view('vendor.training.index', compact('coursesWithProgress', 'user'));
    }

    /**
     * Show a specific course
     */
    public function show(Request $request, int $courseNumber)
    {
        $user = Auth::user();
        $courses = config('training.courses', []);
        
        if (!isset($courses[$courseNumber])) {
            Session::flash('error_flash_message', 'Course not found.');
            return redirect()->route('vendor.training.index');
        }

        $course = $courses[$courseNumber];

        // Check if course is locked
        if ($this->isCourseLocked($user, $course)) {
            Session::flash('error_flash_message', 'You must complete the prerequisite course first.');
            return redirect()->route('vendor.training.index');
        }

        // Get or create progress
        $progress = $user->getCourseProgress($courseNumber);
        if (!$progress) {
            $progress = VendorCourseProgress::create([
                'user_id' => $user->id,
                'course_number' => $courseNumber,
                'course_title' => $course['title'],
            ]);
        }

        // Mark as started if not already
        $progress->markStarted();

        return view('vendor.training.show', compact('course', 'progress', 'user'));
    }

    /**
     * Mark course as completed
     */
    public function complete(Request $request, int $courseNumber)
    {
        $user = Auth::user();
        $courses = config('training.courses', []);
        
        if (!isset($courses[$courseNumber])) {
            Session::flash('error_flash_message', 'Course not found.');
            return redirect()->route('vendor.training.index');
        }

        $course = $courses[$courseNumber];
        $progress = $user->getCourseProgress($courseNumber);

        if (!$progress) {
            Session::flash('error_flash_message', 'Course progress not found.');
            return redirect()->route('vendor.training.index');
        }

        // Calculate time spent (in seconds)
        $timeSpent = 0;
        if ($progress->started_at) {
            $timeSpent = now()->diffInSeconds($progress->started_at);
        }

        // Mark as completed
        $progress->markCompleted($timeSpent);

        Session::flash('flash_message', "Course '{$course['title']}' completed successfully!");

        // Check if there's a next course
        $nextCourse = $user->getNextCourse();
        if ($nextCourse) {
            return redirect()->route('vendor.training.show', $nextCourse['number']);
        }

        // All courses completed
        return redirect('/dashboard')->with('training_complete', true);
    }

    /**
     * Check if a course is locked for the user
     */
    private function isCourseLocked($user, array $course): bool
    {
        if (!isset($course['prerequisite'])) {
            return false;
        }

        return !$user->hasCompletedCourse($course['prerequisite']);
    }
}

