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
        $pieChart->labels(['Submitted', 'Remaining']);
        $lineChart->labels(['Submitted', 'Remaining']);
        $pieChart->dataset('Submitted', 'pie', [40, 10])->options([
            'backgroundColor' => ['green', 'red']
        ]);
        $doughnetChart->labels(['Submitted', 'Remaining']);
        $doughnetChart->dataset('Submitted', 'doughnut', [40, 10])->options([
            'backgroundColor' => ['green', 'red']
        ]);
        $lineChart->dataset('Submitted', 'line', [40, 10])->options([
            'backgroundColor' => ['green']
        ]);
        $lineChart->dataset('Remaining', 'line', [10, 50])->options([
            'backgroundColor' => ['red']
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



    public function getAssignments($batch_no)
    {

        $assignments = Assignment::where('batch_no', $batch_no)->get();
        $assignments->each(function ($assignment) {
            $assignment->answers = $assignment->answer; // Load the related answers
        });
        return response()->json($assignments);
        // return view('student.pages.assignments', compact('assignments'));
    }



    public function uploadAssignmentStudent(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'assignment_id' => ['required'],
            'user_id' => ['required'],
            'answer_file' => ['required', 'file', 'mimes:html,mp4,jpeg,jpg,png', 'max:10240'], // File validation, max size 10MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }



        // check if its not already present
        $checkExisting = Answers::where('user_id', $request->input('user_id'))->where('assignment_id', $request->input('assignment_id'))->first();


        if ($checkExisting) {
            return response()->json([
                "status" => "already present",
                "message" => "Already submitted"
            ]);
        } else {
            // get all data from the form

            $formFields = $request->except('_token');


            // Store the uploaded file
            $formFields['answer_file'] = $request->file('answer_file')->store('assignment_answers', 'public');

            // Update the assignment with the file path
            $answer = Answers::create($formFields);
            $answer->update([
                "status" => 'submitted'
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Submitted successfully'
            ]);
        }
    }




    function getAssignmentStatus(Request $request)
    {
        // $user_id = $request->input('user_id'); // Get user_id from query string
        $getStatus = Answers::where('user_id', 1)->get();
        return response()->json($getStatus);
    }


    function getSubmittedAssignments(Request $request)
    {
        $batch_no = $request->query('batch_no');
        $assignments = Answers::where('status', 'submitted')
            ->whereHas('assignment', function ($query) use ($batch_no) {
                $query->where('batch_no', $batch_no);
            })
            ->with(['assignment', 'user','marks'])
            ->get();

        return response()->json($assignments);
    }
}
