<?php

namespace App\Http\Controllers;

use App\Charts\assignmentChart;
use App\Models\Answers;
use App\Models\Assignment;
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
            "file" => ['required', 'mimes:jpeg,jpg,png,webp,pdf,docx'],
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
        $batch_no = auth()->user()->batch_assigned;
        $assignments = Assignment::where('batch_no', $batch_no)->get();
        $assignments->each(function ($assignment) {
            $assignment->answers = $assignment->answer; // Load the related answers
        });
        $getStatus = Answers::where('user_id', auth()->user()->id)->get();
        return response()->json([
            'assignments' => $assignments,
            'status' => $getStatus
        ]);
        // return view('student.pages.assignments', compact('assignments'));
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



    function getAssignmentStatus(Request $request)
    {
        // $user_id = $request->input('user_id'); // Get user_id from query string
        $getStatus = Answers::where('user_id', auth()->user()->id)->get();
        return response()->json($getStatus);
    }


    function getSubmittedAssignments(Request $request)
    {
        $batch_no = $request->batch_no;
        $assignments = Answers::where('status', 'submitted')
            ->whereHas('assignment', function ($query) use ($batch_no) {
                $query->where('batch_no', $batch_no);
            })
            ->with(['assignment', 'user', 'marks'])
            ->get();

        return response()->json($assignments);
    }
}
