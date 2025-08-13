<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Marks;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class studentController extends Controller
{
    public function countData()
    {
        $student_batch = auth()->user()->batch_assigned;
        $assignments = Assignment::where('batch_no', $student_batch)->where('type', 'assignment')->count();
        $tests = Assignment::where('batch_no', $student_batch)->where('type', 'test')->count();

        return response()->json([
            'assignments' => $assignments,
            'tests' => $tests,
        ]);
    }



    public function updateProfile(Request $request)
    {

        /**
         * @var \App\Models\User $user
         */
        $user = auth()->user();

        // Validate the request
        $request->validate([
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update the username if provided
        if ($request->filled('name')) {
            $user->update([
                "name" => $request->name
            ]);
        }

        // Update the password if provided
        if ($request->filled('password')) {
            $user->update([
                "password" => Hash::make($request->password)
            ]);
        }

        // Update the image if provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            // if ($user->image) {
            //     Storage::delete($user->image);
            // }

            // Store the new image
            $path = $request->file('image')->store('User_images', 'public');
            $user->update([
                "image" => $path
            ]);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }


    // position of student in class




    public function getClassPosition($user_id, Request $request)
    {
        try {
            // Log request initiation
            Log::info('Starting getClassPosition for user_id: ' . $user_id);

            // Check if user is authenticated
            if (!auth()->check()) {
                Log::warning('User not authenticated for user_id: ' . $user_id);
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.'
                ], 401);
            }

            // Get batch_no from authenticated user
            $batch_no = auth()->user()->batch_assigned;

            // Validate batch_no
            if (empty($batch_no)) {
                Log::warning('No batch assigned for user_id: ' . auth()->id());
                return response()->json([
                    'success' => false,
                    'message' => 'No batch assigned to this user.'
                ], 400);
            }
            Log::info('Batch number: ' . $batch_no);

            // Fetch marks for students in the batch, only for marked answers
            $studentMarks = DB::table('users')
                ->select('users.id', 'users.name', DB::raw('COALESCE(SUM(marks.obt_marks), 0) as total_obtained_marks'))
                ->leftJoin('answers', 'users.id', '=', 'answers.user_id') // Changed student_id to user_id
                ->leftJoin('marks', 'answers.id', '=', 'marks.answer_id')
                ->leftJoin('assignments', 'answers.assignment_id', '=', 'assignments.id')
                ->where('assignments.batch_no', $batch_no)
                ->where('answers.marked', true) // Only include marked answers
                ->groupBy('users.id', 'users.name')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => (int) $item->id,
                        'name' => $item->name ?? 'Unknown',
                        'total_obtained_marks' => (float) $item->total_obtained_marks
                    ];
                });

            // Handle case where no students are found
            if ($studentMarks->isEmpty()) {
                Log::info('No students with marked answers found for batch_no: ' . $batch_no);
                return response()->json([
                    'success' => false,
                    'message' => 'No students with marked answers found in this batch.'
                ], 404);
            }

            // Sort students by total marks in descending order
            $studentMarks = $studentMarks->sortByDesc('total_obtained_marks')->values();

            // Check if the requested student exists in the list
            $studentExists = $studentMarks->contains('id', (int) $user_id);
            if (!$studentExists) {
                Log::warning('Student ID ' . $user_id . ' not found in batch_no: ' . $batch_no);
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found in this batch.'
                ], 404);
            }

            Log::info('Successfully fetched class position for user_id: ' . $user_id);
            return response()->json([
                'success' => true,
                'students' => $studentMarks
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching class position for user_id: ' . $user_id . ' | Message: ' . $e->getMessage() . ' | Stack: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching class position: ' . $e->getMessage()
            ], 500);
        }
    }


}