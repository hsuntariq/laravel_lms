<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\User;
use Illuminate\Http\Request;

class teacherController extends Controller
{
    public function getReleventBatches()
    {
        $teacher_id = auth()->user()->id;
        $courses = User::with('courses')->find($teacher_id);
        $batches = Batch::where('teacher', $teacher_id)->get();
        return response()->json([
            "batches" => $batches,
            "courses" => $courses->courses
        ]);
    }







    public function getReleventStudents(Request $request)
    {
        // Get batch number from the request
        $batch_no = $request->batch_no;

        // Get students count based on the batch number
        $studentCount = User::where('role', 'student')
            ->where('batch_assigned', $batch_no)
            ->count();


        return response()->json([
            'students' => $studentCount,
        ]);
    }




    public function getInfoForBatches(Request $request)
    {
        $course_id = $request->course_id;
        $batches = Batch::where('course_id', $course_id)->get();
        return response()->json([
            "batches" => $batches
        ]);
    }
}
