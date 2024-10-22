<?php

namespace App\Http\Controllers;

use App\Charts\attendanceChart;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class attendanceController extends Controller
{
    public function makeCharts()
    {
        $pieChart = new attendanceChart;
        $doughnetChart = new attendanceChart;
        $radarChart = new attendanceChart;
        $pieChart->labels(['presents', 'absents']);
        $pieChart->dataset('presents', 'pie', [40, 10])->options([
            'backgroundColor' => ['green', 'red']
        ]);
        $doughnetChart->labels(['presents', 'absents']);
        $doughnetChart->dataset('presents', 'doughnut', [40, 10])->options([
            'backgroundColor' => ['green', 'red']
        ]);

        return view('student.pages.attendance', compact('pieChart', 'doughnetChart', 'radarChart'));
    }
    public function makeCharts2()
    {
        $pieChart = new attendanceChart;
        $doughnetChart = new attendanceChart;
        $radarChart = new attendanceChart;
        $pieChart->labels(['presents', 'absents']);
        $pieChart->dataset('presents', 'pie', [40, 10])->options([
            'backgroundColor' => ['green', 'red']
        ]);
        $doughnetChart->labels(['presents', 'absents']);
        $doughnetChart->dataset('presents', 'doughnut', [40, 10])->options([
            'backgroundColor' => ['green', 'red']
        ]);

        return view('teacher.pages.view-attendance', compact('pieChart', 'doughnetChart', 'radarChart'));
    }


    public function getStudents(Request $request)
    {
        $course_name = $request->course_name;
        $batch_no = $request->batch_no;
        $students = User::where('role', 'student')->where('batch_assigned', $batch_no)->where('course_assigned', $course_name)->get();
        return response()->json([
            "students" => $students
        ]);
    }

    public function submitAttendance(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'batch_no' => 'required',
            'course_name' => 'required', // Ensure course_name is appropriately validated
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:users,id', // Ensure the student ID exists
            'attendance.*.attendance' => 'required|in:present,absent,leave', // Valid attendance statuses
            'attendance.*.remarks' => 'nullable|string', // Optional remarks
            'attendance.*.topic' => 'nullable|string', // Optional topic name
        ]);

        $batch_no = $validatedData['batch_no'];
        $course_name = $validatedData['course_name']; // Course name can be a string identifier
        $attendanceData = $validatedData['attendance'];

        foreach ($attendanceData as $attendance) {
            // Create or update the attendance record for each student
            Attendance::updateOrCreate(
                [
                    'user_id' => $attendance['student_id'],
                    'batch_no' => $batch_no,
                    'course_id' => $course_name, // Adjust based on your requirements
                    'attendance_date' => now()->toDateString() // Today's date
                ],
                [
                    'status' => $attendance['attendance'], // Store attendance status
                    'remarks' => $attendance['remarks'] ?? null, // Store remarks if provided
                    'topic' => $attendance['topic'] ?? null // Store topic name if provided
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance submitted successfully!'
        ]);
    }
}
