<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    public function signUp(Request $req)
    {
        $formFields = $req->validate([
            'username' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6']
        ]);

        // Hash the password before saving
        $formFields['password'] = Hash::make($formFields['password']);





        // Create user and log them in
        $user = User::create($formFields);
        auth()->login($user);

        // Return success response as JSON
        return response()->json([
            'message' => 'Welcome ' . auth()->user()->username,
            'user' => $user
        ]);
    }

    public function signOut(Request $req)
    {
        auth()->logout();
        session()->invalidate();
        return redirect('/');
    }

    public function signIn(Request $req)
    {
        $formFields = $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt to authenticate the user
        if (auth()->attempt($formFields)) {
            $req->session()->regenerateToken();

            return response()->json([
                'message' => 'Welcome back ' . auth()->user()->name,
                'user' => auth()->user(),
                'role' => auth()->user()->role,
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid Credentials, Try Again'
            ], 401); // 401 Unauthorized
        }
    }

    public function addNewAdmin(Request $req)
    {
        $formFields = $req->validate([
            "username" => ['required', 'min:3'],
            "email" => ['required', 'email'],
            "password" => ['required', 'min:6'],
            "role" => ['required']
        ]);

        // Hash the password before saving
        $formFields['password'] = Hash::make($formFields['password']);

        // Create new admin user
        $admin = User::create($formFields);

        // Return success response
        return response()->json([
            'message' => 'Admin Added Successfully!',
            'admin' => $admin
        ]);
    }
}
