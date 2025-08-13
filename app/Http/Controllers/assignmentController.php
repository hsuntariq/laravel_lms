<?php

namespace App\Http\Controllers;

use App\Charts\assignmentChart;
use App\Models\Answers;
use App\Models\Assignment;
use App\Models\Marks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class assignmentController extends Controller
{
    public function makeCharts()
    {
        $pieChart = new assignmentChart;
        $lineChart = new assignmentChart;
        $doughnetChart = new assignmentChart;
        // $submitted = Answers::where('batch_no', auth()->user()->batch_assigned)->count();
        $pieChart->labels(['Submitted', 'Remaining']);
        $lineChart->labels(['Submitted', 'Remaining']);
        $pieChart->dataset('Submitted', 'pie', [40, 10])->options([
            'backgroundColor' => ['#03C03C', '#F70101']  // Soft green and pinkish red
        ]);
        $doughnetChart->labels(['Submitted', 'Remaining']);
        $doughnetChart->dataset('Submitted', 'doughnut', [40, 10])->options([
            'backgroundColor' => ['#03C03C', '#F70101']  // Soft green and pinkish red
        ]);
        $lineChart->dataset('Submitted', 'line', [40, 10])->options([
            'backgroundColor' => ['#03C03C']  // Soft green and pinkish red
        ]);
        $lineChart->dataset('Remaining', 'line', [10, 50])->options([
            'backgroundColor' => ['#F70101']  // Soft green and pinkish red
        ]);

        return view('teacher.pages.view-assignments', compact('pieChart', 'doughnetChart', 'lineChart'));
    }



    public function uploadAssignment(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "topic" => ['required', 'min:3', 'string'],
            "description" => ['required'],
            "max_marks" => ['required', 'integer'],
            "batch_no" => ['required', 'integer'],
            "deadline" => ['required'],
            "type" => ['required'],
            "file" => ['required', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $formFields = $req->except('_token'); // Exclude the '_token' from the data

        $formFields['file'] = $req->file('file')->store('assignments', 'public');
        Assignment::create($formFields);


        return response()->json([
            'status' => 'success',
            'message' => 'Uploaded successfully'
        ]);
    }



    public function countAssignment(Request $request)
    {
        $batch = $request->query('batch_no');
        $countAssignmets = Assignment::where('batch_no', $batch)->where('type', 'assignment')->count();
        $countTests = Assignment::where('batch_no', $batch)->where('type', 'test')->count();
        return response()->json([
            "assignments" => $countAssignmets,
            "tests" => $countTests,
        ]);
    }



    public function getAssignments()
    {
        $user = auth()->user();
        $batch_no = $user->batch_assigned;

        // Get all assignments for the user's batch
        $assignments = Assignment::where('batch_no', $batch_no)->get();

        // Get all answers by the user, indexed by assignment_id
        $userAnswers = Answers::where('user_id', $user->id)->get()->keyBy('assignment_id');

        // Attach answer and status to each assignment
        $assignments->each(function ($assignment) use ($userAnswers) {
            $answer = $userAnswers->get($assignment->id);

            $assignment->status = $answer ? 'submitted' : 'pending';
            $assignment->answer = $answer;

            if ($assignment->file) {
                $assignment->file_url = '/laravel/public/external_uploads/' . $assignment->file;
            }
        });

        return response()->json([
            'assignments' => $assignments,
        ]);
    }




    public function uploadAssignmentStudent(Request $request)
    {
        $user_id = auth()->id(); // Get the authenticated user ID

        // Validate the request
        $validator = Validator::make($request->all(), [
            'assignment_id' => ['required'],
            'answer_file' => ['required', 'max:10240'], // File validation with allowed types
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if the assignment has already been submitted
        $checkExisting = Answers::where('user_id', $user_id)
            ->where('assignment_id', $request->input('assignment_id'))
            ->first();

        if ($checkExisting) {
            return response()->json([
                "status" => "already present",
                "message" => "Assignment already submitted"
            ], 409); // Use HTTP status code 409 (Conflict) for already existing submissions
        }

        // Prepare form fields
        $formFields = $request->except('_token');
        $formFields['user_id'] = $user_id; // Assign user_id to the form fields

        // Store the uploaded file
        $formFields['answer_file'] = $request->file('answer_file')->store('assignment_answers', 'public');

        // add  batch number
        $formFields['batch_no'] = auth()->user()->batch_assigned;


        // Create a new answer record
        $answer = Answers::create($formFields);
        $answer->update([
            'status' => 'submitted'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Assignment submitted successfully'
        ], 200); // Use HTTP status code 200 (OK) for success
    }



    public function getAssignmentStatus(Request $request)
    {
        // $user_id = $request->input('user_id'); // Get user_id from query string
        $getStatus = Answers::where('user_id', auth()->user()->id)->get();
        return response()->json($getStatus);
    }

    public function getSubmittedAssignments(Request $request)
    {
        $batch_no = $request->batch_no;
        $assignments = Answers::where('status', 'submitted')
            ->whereHas('assignment', function ($query) use ($batch_no) {
                $query->where('batch_no', $batch_no);
            })
            ->with(['assignment', 'user', 'marks'])
            ->get()
            ->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'answer_file' => $assignment->answer_file,
                    'assignment_id' => $assignment->assignment_id,
                    'batch_no' => $assignment->batch_no,
                    'created_at' => $assignment->created_at,
                    'updated_at' => $assignment->updated_at,
                    'marked' => $assignment->marked,
                    'status' => $assignment->status,
                    'assignment' => $assignment->assignment,
                    'user' => $assignment->user,
                    'marks' => $assignment->marks,
                    'comments' => $assignment->marks ? $assignment->marks->comments : null,
                ];
            });

        return response()->json($assignments);
    }


    public function getAssignmentDetails(Request $request)
    {
        try {
            $student = auth()->user();
            $studentBatchNo = $student->batch_assigned;

            // Set time zone explicitly to PKT
            $now = now()->setTimezone('Asia/Karachi'); // PKT is UTC+5
            \Log::info("Current time (PKT): {$now}");

            // Get all assignments for student's batch
            $assignments = Assignment::where('batch_no', $studentBatchNo)
                ->orderBy('deadline', 'asc')
                ->get();

            // Log assignment deadlines for debugging
            foreach ($assignments as $assignment) {
                \Log::info("Assignment ID {$assignment->id} deadline: {$assignment->deadline}");
            }

            // Get all submissions for this student
            $submissions = Answers::where('user_id', $student->id)
                ->whereIn('assignment_id', $assignments->pluck('id'))
                ->get()
                ->keyBy('assignment_id');

            // Process overdue assignments that haven't been submitted
            $debugInfo = [];
            foreach ($assignments as $assignment) {
                $isOverdue = $now->gt($assignment->deadline);
                \Log::info("Assignment ID {$assignment->id} overdue check: now({$now}) > deadline({$assignment->deadline}) = {$isOverdue}");

                if ($isOverdue && !isset($submissions[$assignment->id])) {
                    // Validate required fields
                    if (!isset($assignment->max_marks)) {
                        \Log::error("Missing max_marks for assignment ID: {$assignment->id}");
                        $debugInfo[] = "Failed to mark assignment ID {$assignment->id}: Missing max_marks or course_no";
                        continue;
                    }

                    // Create an Answers entry
                    $answer = Answers::create([
                        'user_id' => $student->id,
                        'assignment_id' => $assignment->id,
                        'batch_no' => $studentBatchNo,
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
                                'batch_no' => $studentBatchNo,
                            ]);

                            // Update Answers to reflect marked status
                            $answer->update(['marked' => true]);
                            $debugInfo[] = "Marked assignment ID {$assignment->id} with 0";
                        } catch (\Exception $e) {
                            \Log::error("Failed to create Marks for assignment ID {$assignment->id}: " . $e->getMessage());
                            $debugInfo[] = "Failed to mark assignment ID {$assignment->id}: " . $e->getMessage();
                        }
                    } else {
                        $debugInfo[] = "Assignment ID {$assignment->id} already marked";
                    }

                    // Update submissions collection
                    $submissions[$assignment->id] = $answer;
                }
            }

            // Filter pending assignments
            $pendingAssignments = $assignments->filter(function ($assignment) use ($submissions, $now) {
                $isSubmitted = isset($submissions[$assignment->id]);
                $isActive = $now->lt($assignment->deadline);
                return !$isSubmitted && $isActive;
            });

            // Prepare response data
            $formattedAssignments = $assignments->map(function ($assignment) use ($submissions, $now) {
                $submission = $submissions[$assignment->id] ?? null;
                $isOverdue = $now->gt($assignment->deadline);

                return [
                    'id' => $assignment->id,
                    'topic' => $assignment->topic,
                    'deadline' => $assignment->deadline,
                    'is_submitted' => !is_null($submission),
                    'is_overdue' => $isOverdue,
                    'status' => !is_null($submission) ? $submission->status : ($isOverdue ? 'overdue' : 'pending')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'assignments' => $formattedAssignments,
                    'statistics' => [
                        'pending_count' => $pendingAssignments->count(),
                        'total_count' => $assignments->count(),
                    ],
                    'debug' => $debugInfo
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error("getAssignmentDetails error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'debug' => $debugInfo
            ], 500);
        }
    }


    // app/Http/Controllers/StudentDashboardController.php
    public function show()
    {
        try {
            // Get current datetime in the same format as your deadline field
            $now = now()->toDateTimeString();

            // Get latest 3 assignments for the batch that haven't passed deadline
            $assignments = Assignment::where('batch_no', auth()->user()->batch_assigned)
                ->where('deadline', '>=', $now)  // Only future deadlines
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get(['id', 'topic', 'description', 'deadline', 'created_at', 'type']);

            return response()->json([
                'success' => true,
                'data' => $assignments,
                'message' => 'Successfully retrieved latest assignments'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve assignments: ' . $e->getMessage()
            ], 500);
        }
    }


}
