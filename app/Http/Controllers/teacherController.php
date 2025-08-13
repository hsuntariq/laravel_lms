<?php

namespace App\Http\Controllers;

use App\Charts\teacherDashboardChart;
use App\Models\Answers;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\Marks;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Validator;

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

    public function getAssignmentsByBatch(Request $req)
    {
        $batch_no = $req->query('batch_no');
        $assignments = Assignment::where("batch_no", $batch_no)->get();
        return response()->json($assignments);
    }


    // edit the assignment
    public function editAssignment(Request $request, $user_id)
    {

        $validator = Validator::make($request->all(), [
            'assignment_id' => 'required|exists:assignments,id',
            'topic' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_marks' => 'required|numeric|min:0',
            'batch_no' => 'required|integer',
            'deadline' => 'nullable|date',
            'type' => 'nullable|string',
            'file' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $assignment = Assignment::findOrFail($data['assignment_id']);

        // Log request data for debugging
        Log::info('Edit Assignment Request:', ['data' => $data, 'current_deadline' => $assignment->deadline]);

        // Check if deadline has changed

        // Update assignment
        $assignment->update([
            'topic' => $data['topic'],
            'description' => $data['description'],
            'max_marks' => $data['max_marks'],
            'batch_no' => $data['batch_no'],
            'deadline' => $data['deadline'],
            'type' => $data['type'],
            'file' => $data['file'],
        ]);

        // If deadline changed, delete answers with null answer_file for the relevant assignment_id
        $deletedCount = 0;
        $deletedCount = Answers::where('assignment_id', $assignment->id)
            ->whereNull('answer_file')
            ->delete();
        Log::info('Deleted ' . $deletedCount . ' answers with assignment_id ' . $assignment->id);


        return response()->json(['message' => 'Assignment updated successfully', 'deleted_answers' => $deletedCount]);
    }



    public function deleteAssignment(Request $request)
    {
        $assignment = Assignment::findOrFail($request->id);
        $assignment->delete();
        return response()->json(['message' => 'Assignment deleted']);
    }



    public function markOverdueAssignments(Request $request)
    {
        $debugInfo = [];
        $markedAssignments = 0;
        $skippedAssignments = 0;
        try {
            $batch_no = $request->input('batch_no');
            $course_no = $request->input('course_no');

            // Validate inputs
            if (!$batch_no) {
                \Log::error("Missing batch_no in markOverdueAssignments");
                return response()->json([
                    'success' => false,
                    'message' => 'Batch number and course number are required',
                    'debug' => []
                ], 400);
            }

            // Set timezone to PKT (Asia/Karachi)
            $now = Carbon::now()->setTimezone('Asia/Karachi');
            \Log::info("Current time (PKT) for markOverdueAssignments: {$now}");

            // Get all students in the batch for the course
            $students = User::where('batch_assigned', $batch_no)->get();

            if ($students->isEmpty()) {
                \Log::info("No students found for batch_no: {$batch_no}");
                return response()->json([
                    'success' => false,
                    'message' => 'No students found for the specified batch and course',
                    'debug' => []
                ], 404);
            }

            // Get all assignments for the batch and course
            $assignments = Assignment::where('batch_no', $batch_no)
                ->orderBy('deadline', 'asc')
                ->get();

            if ($assignments->isEmpty()) {
                \Log::info("No assignments found for batch_no: {$batch_no}");
                return response()->json([
                    'success' => false,
                    'message' => 'No assignments found for the specified batch and course',
                    'debug' => []
                ], 404);
            }



            foreach ($students as $student) {
                // Get all submissions for this student
                $submissions = Answers::where('user_id', $student->id)
                    ->whereIn('assignment_id', $assignments->pluck('id'))
                    ->get()
                    ->keyBy('assignment_id');

                foreach ($assignments as $assignment) {
                    $isOverdue = $now->gt($assignment->deadline);
                    \Log::info("Checking assignment ID {$assignment->id} for student ID {$student->id}: now({$now}) > deadline({$assignment->deadline}) = {$isOverdue}");

                    if ($isOverdue && !isset($submissions[$assignment->id])) {
                        // Validate required fields
                        if (!isset($assignment->max_marks)) {
                            \Log::error("Missing max_marks for assignment ID: {$assignment->id}");
                            $debugInfo[] = "Failed to mark assignment ID {$assignment->id} for student ID {$student->id}: Missing max_marks";
                            $skippedAssignments++;
                            continue;
                        }

                        // Create an Answers entry
                        $answer = Answers::create([
                            'user_id' => $student->id,
                            'assignment_id' => $assignment->id,
                            'batch_no' => $batch_no,
                            'status' => 'overdue',
                            'answer_file' => null,
                        ]);

                        // Check if already marked
                        $existingMark = Marks::where('assignment_id', $assignment->id)
                            ->where('answer_id', $answer->id)
                            ->where('user_id', $student->id)
                            ->first();

                        if (!$existingMark) {
                            try {
                                // Create a Marks entry
                                Marks::create([
                                    'assignment_id' => $assignment->id,
                                    'answer_id' => $answer->id,
                                    'user_id' => $student->id,
                                    'obt_marks' => 0,
                                    'max_marks' => $assignment->max_marks,
                                    'comments' => 'Automatically marked 0 due to missed deadline',
                                    'batch_no' => $batch_no,
                                ]);

                                // Update Answers to reflect marked status
                                $answer->update(['marked' => true]);
                                $debugInfo[] = "Marked assignment ID {$assignment->id} with 0 for student ID {$student->id}";
                                $markedAssignments++;
                            } catch (\Exception $e) {
                                \Log::error("Failed to create Marks for assignment ID {$assignment->id} for student ID {$student->id}: " . $e->getMessage());
                                $debugInfo[] = "Failed to mark assignment ID {$assignment->id} for student ID {$student->id}: " . $e->getMessage();
                                $skippedAssignments++;
                            }
                        } else {
                            $debugInfo[] = "Assignment ID {$assignment->id} already marked for student ID {$student->id}";
                            $skippedAssignments++;
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Processed overdue assignments: {$markedAssignments} marked, {$skippedAssignments} skipped",
                'debug' => $debugInfo
            ]);

        } catch (\Exception $e) {
            \Log::error("markOverdueAssignments error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'debug' => $debugInfo
            ], 500);
        }
    }




    public function getAllStudents(Request $request, $user_id)
    {
        try {
            $batch_no = $request->input('batch_no');
            $course_no = $request->input(key: 'course_no');

            // Query to get all students for the given batch and course
            $students = User::where('batch_assigned', $batch_no)->get();

            $formattedStudents = $students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'image' => $student->image,
                ];
            });

            return response()->json([
                'success' => true,
                'students' => $formattedStudents
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching students: ' . $e->getMessage()
            ], 500);
        }
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
    // ...existing code...

    public function getBatchScheduleDays(Request $request)
    {
        $course_id = $request->input('course_id');
        $batch_no = $request->input('batch_no');

        $batch = Batch::where('course_id', $course_id)
            ->where('id', $batch_no)
            ->first();

        if (!$batch) {
            return response()->json(['success' => false, 'message' => 'Batch not found']);
        }

        $days = json_decode($batch->days, true);
        $class_links = json_decode($batch->class_links, true);

        // Use PKT timezone
        $now = Carbon::now('Asia/Karachi');
        $today = $now->toDateString();

        // Build start and end DateTime for today
        $start_time = $batch->start_time;
        $end_time = $batch->end_time; // Assuming end_time is a column in the Batch model

        $startDateTime = Carbon::parse($today . ' ' . $start_time, 'Asia/Karachi');
        $can_start = false;
        $in_window = false;

        if ($end_time) {
            $endDateTime = Carbon::parse($today . ' ' . $end_time, 'Asia/Karachi');
            $in_window = $now->between($startDateTime, $endDateTime);
            $can_start = $in_window;
        } else {
            // Allow if now >= start time (no end time restriction)
            $can_start = $now->greaterThanOrEqualTo($startDateTime);
        }

        return response()->json([
            'batch' => $batch,
            'success' => true,
            'start_date' => $batch->start_date,
            'end_date' => $batch->end_date,
            'days' => $days,
            'class_links' => $class_links,
            'start_time' => $batch->start_time,
            'end_time' => $batch->end_time ?? null,
            'can_start' => $can_start,
            'now' => $now->toDateTimeString(),
            'start_datetime' => $startDateTime->toDateTimeString(),
            'in_window' => $in_window,
        ]);
    }



}