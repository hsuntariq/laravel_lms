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


    public function getReleventStudents($batch_no)
    {
        $students = User::where('role', 'student')->where('batch_assigned', $batch_no)->count();
        $courses = User::with('courses')->where('role', 'teacher')->get();
        return response()->json([
            "students" => $students,
            "courses" => $courses
        ]);
    }
}
