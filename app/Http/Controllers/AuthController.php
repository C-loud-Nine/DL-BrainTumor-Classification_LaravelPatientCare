<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Handle user registration
    public function registerUser(Request $request)
    {
        // Validate the form input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8', // Confirm password validation
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // File upload validation
        ]);

        // Create and save the new user
        $newUser = new User();
        $newUser->name = $request->input('name');
        $newUser->email = $request->input('email');
        $newUser->password = Hash::make($request->input('password'));  // Hash the password

        // Handle file upload
        if ($request->hasFile('file')) {
            $newUser->picture = $request->file('file')->getClientOriginalName();  // Get original filename
            $request->file('file')->move(public_path('uploads/profile/'), $newUser->picture);  // Move the file to storage
        }

        // Set default user type
        $newUser->type = "user";

        // Save the user to the database
        if ($newUser->save()) {
            return redirect()->route('login')->with('success', 'User Registered Successfully!');
        }

        // If user registration fails, redirect back with error
        return redirect()->back()->with('error', 'Failed to register user.');
    }


    // Handle user login
    public function loginUser(Request $request)
    {
        // Validate the form input
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Check if the email exists
        $user = User::where('email', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            // Password is correct, log the user in
            session([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_picture' => $user->picture,
                'user_type' => $user->type,
            ]);
            
            if ($user->type == "user") {
                return redirect()->route('home')->with('success', 'Login successful!');
            } else if ($user->type == "admin") {
                // dd('Admin user logged in, redirecting to admin home');
                return redirect()->route('admin.adminhome'); // Redirect to the admin home page
            }


    
        }

        // If login fails, redirect back with error
        abort(403, 'Invalid email or password.');
    }

    // Handle user logout
    public function logoutUser()
    {
        session()->flush();  // Remove all session data
        return redirect()->route('home')->with('success', 'You are logged out!');
    }
}
