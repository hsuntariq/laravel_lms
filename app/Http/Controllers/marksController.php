<?php

namespace App\Http\Controllers;

use App\Models\Answers;
use App\Models\Marks;
use Illuminate\Http\Request;

class marksController extends Controller
{
    public function markAssignment(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'answer_id' => 'required|exists:answers,id',
            'user_id' => 'required|exists:users,id',
            'obt_marks' => 'required|integer|min:0',
            'comments' => 'nullable|string',
            'max_marks' => 'required',
            'batch_no' => 'required',
            'course_no' => 'required'
        ]);

        // Check if the assignment has already been marked
        $existingMark = Marks::where('assignment_id', $validatedData['assignment_id'])
            ->where('answer_id', $validatedData['answer_id'])
            ->where('user_id', $validatedData['user_id'])
            ->first();

        if ($validatedData['obt_marks'] > $validatedData['max_marks']) {
            return response()->json([
                "status" => 'error',
                'message' => 'Invalid Obtained marks, obtained marks should be less than or equal to the max marks'
            ], 400);
        }



        if ($existingMark) {
            return response()->json([
                'status' => 'error',
                'message' => 'This assignment has already been marked.',
            ], 400);
        }

        // Create a new mark entry
        $marks = Marks::create($validatedData);


        // update the marked status
        $assignment = Answers::where('assignment_id', $validatedData['assignment_id'])->where('user_id', $validatedData['user_id'])->first();

        $assignment->update([
            'marked' => true,
        ]);


        // Respond with success
        return response()->json([
            'status' => 'success',
            'message' => 'Assignment marked successfully',
            'marks' => $marks,
        ]);
    }


    public function getMarks(Request $request)
    {
        $user_id = auth()->user()->id;

        // Fetch marks with eager loading, including both 'answer' and 'assignment'
        $marks = Marks::where('user_id', $user_id)
            ->whereHas('answer', function ($query) {
                $query->where('marked', true);
            })
            ->with(['answer.assignment']) // Eager load both the answer and the assignment through the answer
            ->get();

        return response()->json($marks);
    }
}
