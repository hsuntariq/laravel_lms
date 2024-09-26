<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class staffController extends Controller
{
    public function addInstructor(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'whatsapp' => 'required|string|max:15',
            'course_assigned' => 'required|string',
            'gender' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle file upload if image is provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('instructors', 'public');
        }

        // Create the instructor
        $instructor = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'whatsapp' => $request->whatsapp,
            'course_assigned' => $request->course_assigned,
            'gender' => $request->gender,
            'image' => $imagePath,
            'role' => 'teacher', // Fixed role as 'teacher'
        ]);

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Instructor added successfully',
            'data' => $instructor
        ]);
    }


    public function storeStudent(Request $request)
    {
        // Validate the incoming student data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'whatsapp' => 'nullable|string',
            'course_assigned' => 'required|exists:courses,id',
            'batch_assigned' => 'required|exists:batches,id',
            'gender' => 'required|in:male,female',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('student_images', 'public');
            $validated['image'] = $path;
        }

        // Hash the password before saving to the database
        $validated['password'] = Hash::make($validated['password']);

        // Create the student
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'whatsapp' => $validated['whatsapp'],
            'gender' => $validated['gender'],
            'image' => $validated['image'] ?? null,
            'course_assigned' => $validated['course_assigned'],
            'batch_assigned' => $validated['batch_assigned'],
            'role' => 'student', // Role set to 'student'
        ]);

        // Redirect with a success message
        return redirect()->route('students.index')->with('success', 'Student added successfully.');
    }



    public function getBatchesStudent(Request $request)
    {
        $batches = Batch::where('course_id', $request->course_id)->get();
        return response()->json(['batches' => $batches]);
    }
}
