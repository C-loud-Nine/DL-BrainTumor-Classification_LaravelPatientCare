<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Specialization;
use App\Models\Appointment;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class AdminController extends Controller
{
    // Admin home page
    // public function adminHome()
    // {
    //     return view('admin.adminhome');
    // }


    public function adminHome()
{
    // Fetch reports grouped by the 'report_class' field
    $reportCounts = Report::select('report_class', Report::raw('count(*) as total'))
        ->groupBy('report_class')
        ->get();

    // Fetch user counts by roles (assuming you have 'admin' and 'doctor' roles)
    $adminCount = User::where('type', 'admin')->count();
    $doctorCount = User::where('type', 'doctor')->count();
    $userCount = User::where('type', 'user')->count();
    $totCount = User::count(); // Total users (admins + doctors + other roles)

    // Pass data to the view
    return view('admin.adminhome', compact('reportCounts', 'adminCount', 'doctorCount', 'userCount' , 'totCount'));
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
    $appointments = Appointment::orderBy('status', 'asc')
    ->whereIn('status', ['pending', 'rejected' , 'approved'])              
    ->get(); // Sort by status
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


public function showConfirmedAppointments()
    {
        // Fetch only confirmed appointments
        $confirmedAppointments = Appointment::where('status', 'confirmed')->get();

        return view('admin.appointmentconfirm', compact('confirmedAppointments'));
    }

    public function updateAppointmentStatus(Request $request, $id)
{
    $appointment = Appointment::find($id);

    if ($appointment) {
        // Update status to "pending"
        $appointment->status = 'pending';
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment updated successfully.');
    }

    return redirect()->back()->with('error', 'Appointment not found.');
}


    // Delete appointment
    public function deleteConfirmedAppointment($id)
    {
        $appointment = Appointment::find($id);

        if ($appointment) {
            // Update status to "pending"
            $appointment->status = 'removed';
            $appointment->save();
    
            return redirect()->back()->with('success', 'Appointment removed successfully.');
        }

        return redirect()->back()->with('error', 'Appointment not found.');
    }




    public function adminDocApp()
    {
        // Retrieve the unique doctor names from the appointments table
        $doctors = Appointment::select('doctor')->distinct()->pluck('doctor');

        return view('admin.admindoctorapp', compact('doctors'));
    }



    

    public function fetchDoctorAppointments(Request $request)
{
    $doctorName = $request->input('doctor');
    $appointments = Appointment::where('doctor', $doctorName)
        ->where('status', 'confirmed')
        ->orderBy('date', 'asc')
        ->get()
        ->groupBy('date');

    return response()->json($appointments);
}


public function viewNoShowAppointments()
{
    // Fetch appointments with status 'approved' and updated_at older than 24 hours
    $appointments = DB::table('appointments')
        ->where('status', 'approved')
        ->where('updated_at', '<', Carbon::now()->subHours(24))
        ->get();

    return view('admin.adminnoshow', compact('appointments'));
}





public function sendConfirmationEmail($id)
{
    $appointment = Appointment::findOrFail($id);

    $email = $appointment->email;
    $data = [
        'name' => $appointment->name,
        'doctor' => $appointment->doctor,
        'date' => $appointment->date,
        'message_content' => $appointment->message, // Renamed to avoid conflict
    ];

    try {
        Mail::send('email.appointment_confirmation', $data, function ($mailMessage) use ($email) {
            $mailMessage->to($email)
                        ->subject('Appointment Confirmation');
        });

        return back()->with('success', 'Confirmation email sent successfully.');
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to send email: ' . $e->getMessage());
    }
}


    public function showRemovedAppointments()
    {
        // Fetch only confirmed appointments
        $Appointments = Appointment::where('status', 'removed')->get();

        return view('admin.appointmentremoved', compact('Appointments'));
    }


    public function deleteRemovedAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        try {
            $appointment->delete();
            return redirect()->route('admin.appointmentremoved')->with('success', 'Appointment deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.appointmentremoved')->with('error', 'Failed to delete the appointment.');
        }
    }


}





