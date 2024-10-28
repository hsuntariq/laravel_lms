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

        $attendanceQuery = Attendance::where('batch_no', auth()->user()->batch_assigned)
            ->where('course_id', auth()->user()->course_assigned)
            ->where('user_id', auth()->user()->id);

        $presents = $attendanceQuery->where('status', 'present')->count();
        $absents = $attendanceQuery->where('status', 'absent')->count();
        $pieChart->labels(['Presents', 'Absents']);
        $pieChart->dataset('Attendance', 'pie', [$presents, $absents])->options([
            'backgroundColor' => ['#03C03C', '#F70101']  // Soft green and pinkish red
        ]);
        $doughnetChart->labels(['presents', 'absents']);
        $doughnetChart->dataset('presents', 'doughnut', [$presents, $absents])->options([
            'backgroundColor' => ['#03C03C', '#F70101']
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

        // Fetch students who belong to the specified batch and course
        $students = User::where('role', 'student')
            ->where('batch_assigned', $batch_no)
            ->where('course_assigned', $course_name)
            ->get();

        // Calculate the attendance percentage for each student
        $studentsWithAttendance = $students->map(function ($student) use ($batch_no, $course_name) {
            // Fetch all attendance records for the student for the given batch and course
            $attendanceRecords = Attendance::where('user_id', $student->id)
                ->where('batch_no', $batch_no)
                ->where('course_id', $course_name)
                ->get();

            // Total classes based on distinct attendance dates
            $totalClasses = $attendanceRecords->groupBy('attendance_date')->count();

            // Count the number of times the student was present or on leave
            $presentCount = $attendanceRecords->whereIn('status', ['present', 'leave'])->count();

            // Calculate attendance percentage (leave is counted as present)
            $attendancePercentage = $totalClasses > 0 ? ($presentCount / $totalClasses) * 100 : 0;

            // Append the attendance percentage to the student data
            $student->attendance_percentage = round($attendancePercentage, 2); // round to 2 decimal places

            return $student;
        });

        return response()->json([
            "students" => $studentsWithAttendance
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
            'attendance.*.topic' => 'string', // Optional topic name
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
                    'topic' => $attendance['topic'] ?? 'web development' // Store topic name if provided
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance submitted successfully!'
        ]);
    }



    public function checkAttendanceMarked(Request $request)
    {
        $batch_no = $request->batch_no;
        $course_name = $request->course_name;

        // Check if attendance has been marked for the current date
        $attendanceExists = Attendance::where('batch_no', $batch_no)
            ->where('course_id', $course_name)
            ->where('attendance_date', now()->toDateString()) // Current date
            ->exists();

        return response()->json([
            'attendance_marked' => $attendanceExists,
        ]);
    }


    public function totalClasses(Request $request)
    {
        // Fetch all attendance records for the student for the given batch and course
        $batch_no = auth()->user()->batch_assigned;
        $course_name = auth()->user()->course_assigned;
        $attendanceRecords = Attendance::where('batch_no', $batch_no)
            ->where('course_id', $course_name)->where('user_id', auth()->user()->id)
            ->get();

        // Total classes based on distinct attendance dates
        $totalClasses = $attendanceRecords->groupBy('attendance_date')->count();
        $totalPresents = $attendanceRecords->where('status', 'present')->groupBy('attendance_date')->count();
        $totalAbsents = $attendanceRecords->where('status', 'absent')->groupBy('attendance_date')->count();


        return response()->json([
            "totalClasses" => $totalClasses,
            "presents" => $totalPresents,
            "absents" => $totalAbsents,
        ]);
    }



    public function getStudentAttendace(Request $request)
    {
        $batch_no = auth()->user()->batch_assigned;
        $course_no = auth()->user()->course_assigned;


        $records = Attendance::where('batch_no', $batch_no)->where('course_id', $course_no)->where('user_id', auth()->user()->id)->get();

        return response()->json([
            'attendance' => $records
        ]);
    }
}
