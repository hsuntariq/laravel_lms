<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Marks;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $course_no = $request->course_no;

        // Get students count based on the batch number
        $studentCount = User::where('role', 'student')
            ->where('batch_assigned', $batch_no)->where('course_assigned', $course_no)
            ->count();

        $students = DB::table('marks')
            ->select(
                'user_id',
                DB::raw('SUM(obt_marks) as total_obtained_marks'),
                DB::raw('SUM(max_marks) as total_max_marks')
            )
            ->groupBy('user_id')
            ->get();

        // Categorize students
        $excellingStudents = [];
        $averageStudents = [];
        $strugglingStudents = [];

        foreach ($students as $student) {
            $percentage = ($student->total_obtained_marks / $student->total_max_marks) * 100;

            if ($percentage > 80) {
                $excellingStudents[] = $student;
            } elseif (
                $percentage > 50 && $percentage <= 80
            ) {
                $averageStudents[] = $student;
            } else {
                $strugglingStudents[] = $student;
            }
        }
        return response()->json([
            'students' => $studentCount,
            'excelling_students' => $excellingStudents,
            'average_students' => $averageStudents,
            'struggling_students' => $strugglingStudents,
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



    // check if the attendance has been marked for current date

}
