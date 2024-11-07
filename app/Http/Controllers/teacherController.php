<?php

namespace App\Http\Controllers;

use App\Charts\teacherDashboardChart;
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
            ->join('users', 'marks.user_id', '=', 'users.id')
            ->where('users.batch_assigned', $batch_no)
            ->where('users.course_assigned', $course_no)
            ->where('users.role', 'student')
            ->select(
                'marks.user_id',
                'users.name',
                'users.email',
                'users.image',
                DB::raw('SUM(marks.obt_marks) as total_obtained_marks'),
                DB::raw('SUM(marks.max_marks) as total_max_marks')
            )
            ->groupBy('marks.user_id', 'users.name', 'users.email', 'users.image') // Include users.image here
            ->get();

        // Categorize students
        $excellingStudents = [];
        $averageStudents = [];
        $strugglingStudents = [];

        foreach ($students as $student) {
            $percentage = ($student->total_obtained_marks / $student->total_max_marks) * 100;

            if ($percentage >= 70) {
                $excellingStudents[] = $student;
            } elseif (
                $percentage > 50 && $percentage < 70
            ) {
                $averageStudents[] = $student;
            } else {
                $strugglingStudents[] = $student;
            }
        }


        // chart for performance

        $doughnetChart = new teacherDashboardChart;

        $doughnetChart->dataset('excelling', 'doughnut', [count($excellingStudents), count($averageStudents), count($strugglingStudents)])->options([
            'backgroundColor' => ['green', 'yellow', 'red']
        ]);




        return response()->json([
            'students' => $studentCount,
            'excelling_students' => $excellingStudents,
            'average_students' => $averageStudents,
            'struggling_students' => $strugglingStudents,
            'doughnet_chart' => $doughnetChart,
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
