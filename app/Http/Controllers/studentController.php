<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        // Update the password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update the image if provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($user->image) {
                Storage::delete($user->image);
            }

            // Store the new image
            $path = $request->file('image')->store('user_images');
            $user->image = $path;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }
}
