<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Specialization;
use App\Models\Appointment;

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


    public function adminprofile()
    {
        // Retrieve the currently authenticated user using session
        if (!session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Please log in to view your profile.');
        }
 
        $admin = User::find(session('user_id'));
 
        return view('admin.adminprofile', compact('admin'));
    }

     
    // Update Admin Profile
    public function updateAdminProfile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $admin = User::find($id);

        if (!$admin) {
            return redirect()->route('admin.adminprofile')->with('error', 'User not found.');
        }

        // Update details
        $admin->name = $request->name;
        $admin->location = $request->location;

        // Update password if provided
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }

        // Update profile picture if provided
        if ($request->hasFile('profile_picture')) {
            $imageName = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->move(public_path('uploads/profile'), $imageName);
            $admin->picture = $imageName;
        }

        $admin->save();

        return redirect()->route('admin.adminprofile')->with('success', 'Profile updated successfully.');
    }

    // Delete Admin Profile
    public function deleteAdminProfile($id)
    {
        $admin = User::find($id);

        if (!$admin) {
            return redirect()->route('admin.adminprofile')->with('error', 'User not found.');
        }

        // Delete admin profile
        $admin->delete();

        return redirect()->route('login')->with('success', 'Profile deleted successfully.');
    }

    
    
    // Show specialization management page
    public function adminspecial()
    {
        // Get all specializations from the database
        $specializations = Specialization::all();

        // Pass them to the view
        return view('admin.adminspecial', compact('specializations'));
    }

    // Store a new specialization
    public function storeSpecialization(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:specializations,name', // Ensure uniqueness
        ]);

        $specialization = new Specialization;
        $specialization->name = $request->name;
        $specialization->save();

        return redirect()->route('admin.adminspecial')->with('success', 'Specialization added successfully.');
    }

    // Delete a specialization
    public function destroySpecialization($id)
    {
        $specialization = Specialization::findOrFail($id);
        $specialization->delete();

        return redirect()->route('admin.adminspecial')->with('success', 'Specialization deleted successfully.');
    }


    public function updateSpecialization(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:specializations,name,' . $id,
    ]);

    $specialization = Specialization::findOrFail($id);
    $specialization->name = $request->name;
    $specialization->save();

    return redirect()->route('admin.adminspecial')->with('success', 'Specialization updated successfully.');
}





// Show appointments for admin
public function showAppointments()
{
    $appointments = Appointment::orderBy('status', 'asc')->get(); // Sort by status
    return view('admin.appointmentapprove', compact('appointments'));
}

// Update appointment status
public function updateAppointment(Request $request, $id)
{
    $appointment = Appointment::findOrFail($id);
    
    // Update the appointment
    $appointment->date = $request->input('date');
    $appointment->status = $request->input('status');
    $appointment->save();

    return redirect()->route('admin.appointmentapprove')->with('success', 'Appointment updated successfully.');
}


public function deleteAppointment($id)
{
    $appointment = Appointment::findOrFail($id);

    try {
        $appointment->delete();
        return redirect()->route('admin.appointmentapprove')->with('success', 'Appointment deleted successfully.');
    } catch (\Exception $e) {
        return redirect()->route('admin.appointmentapprove')->with('error', 'Failed to delete the appointment.');
    }
}



}
