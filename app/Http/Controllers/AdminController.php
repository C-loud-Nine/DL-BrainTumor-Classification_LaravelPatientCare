<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    // Admin home page
    public function adminHome()
    {
        return view('admin.adminhome');
    }

    // User list page
    public function userlist()
    {
        $users = User::where('type', 'user')->get();
        return view('admin.userlist', compact('users'));
    }

    // Method to promote a user
    public function add_promotion($id, $role)
    {
        // Find the user by ID
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Update the user role based on the requested role
        if ($role == 'doctor') {
            $user->type = 'doctor';
        } elseif ($role == 'admin') {
            $user->type = 'admin';
        }

        // Save the updated user
        $user->save();

        return redirect()->back()->with('success', 'User promoted successfully.');
    }



    // Method to promote a user
    public function demotion($id)
    {
        // Find the user by ID
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $user->type = 'user';

        // Save the updated user
        $user->save();

        return redirect()->back()->with('success', 'User promoted successfully.');
    }



    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
    
        return redirect()->back()->with('success', 'User deleted successfully');
    }


    public function adminlist()
    {
        $admins = User::where('type', 'admin')->get();
        return view('admin.adminlist', compact('admins'));
    }


    public function doctorlist()
    {
        $docs = User::where('type', 'doctor')->get();
        return view('admin.doctorlist', compact('docs'));
    }



}
