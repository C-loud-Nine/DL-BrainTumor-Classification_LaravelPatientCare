<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Doctor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('user.home');
    }

    public function login()
    {
        return view('user.login');
    }

    public function register()
    {
        return view('user.register');
    }

   // Show the user's profile
   public function userprofile()
   {
       // Retrieve the currently authenticated user using session
       if (!session()->has('user_id')) {
           return redirect()->route('login')->with('error', 'Please log in to view your profile.');
       }

       $user = User::find(session('user_id'));

       return view('user.userprofile', compact('user'));
   }

   /**
    * Update the user's profile.
    */
   public function updateUserProfile(Request $request, $id)
   {



       // Validate input
    $request->validate([
        'name' => 'required|string|max:255',
        'location' => 'nullable|string|max:255',
        'password' => 'nullable|string|min:8',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Find the user
    $user = User::findOrFail($id);

    // Update user fields
    $user->name = $request->name;
    $user->location = $request->location;

    // Update password if provided
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // Handle profile picture upload
    if ($request->hasFile('profile_picture')) {
        $file = $request->file('profile_picture');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/profile'), $filename);
        $user->picture = $filename;
    }

    // Save the user
    $user->save();

    // Redirect with success message
    return redirect()->back()->with('success', 'Profile updated successfully!');
   
}

   /**
    * Delete the user's profile.
    */
   public function deleteUserProfile($id)
   {
       // Check if session contains the user ID and ensure user ID matches the session
       if (!session()->has('user_id') || session('user_id') != $id) {
           return redirect()->route('login')->with('error', 'Unauthorized access.');
       }

       $user = User::findOrFail($id);

       // Delete the user's profile picture if exists
       if ($user->picture && file_exists(public_path('uploads/profile/' . $user->picture))) {
           unlink(public_path('uploads/profile/' . $user->picture));
       }

       $user->delete();

       // Clear session data
       session()->flush();

       return redirect()->route('home')->with('success', 'Your profile has been deleted.');
   }



    
//       public function doctorprofile()
//       {
//           // Retrieve the currently authenticated user using session
//           if (!session()->has('user_id')) {
//               return redirect()->route('login')->with('error', 'Please log in to view your profile.');
//           }
   
//           $doc = User::find(session('user_id'));
   
//           return view('user.doctorprofile', compact('doc'));
//       }
   
    
//       public function updateDoctorProfile(Request $request, $id)
//       {
   
       
   
//           // Validate input
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'location' => 'nullable|string|max:255',
//            'password' => 'nullable|string|min:8',
//            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//        ]);
   
//        // Find the user
//        $doc = User::findOrFail($id);
   
//        // Update user fields
//        $doc->name = $request->name;
//        $doc->location = $request->location;
   
//        // Update password if provided
//        if ($request->filled('password')) {
//            $doc->password = Hash::make($request->password);
//        }
   
//        // Handle profile picture upload
//        if ($request->hasFile('profile_picture')) {
//            $file = $request->file('profile_picture');
//            $filename = time() . '.' . $file->getClientOriginalExtension();
//            $file->move(public_path('uploads/profile'), $filename);
//            $doc->picture = $filename;
//        }
   
//        // Save the user
//        $doc->save();
   
//        // Redirect with success message
//        return redirect()->back()->with('success', 'Profile updated successfully!');
      
//    }



public function doctorprofile()
{
    // Retrieve the currently authenticated user using session
    if (!session()->has('user_id')) {
        return redirect()->route('login')->with('error', 'Please log in to view your profile.');
    }

    // Fetch the user's doctor data
    $doc = User::with('doctor')->find(session('user_id'));

    return view('user.doctorprofile', compact('doc'));
}



public function updateDoctorProfile(Request $request, $id)
{
    // Validate input
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:15',
        'specialization' => 'nullable|string|max:255',
        'room' => 'nullable|string|max:50',
        'appointment' => 'nullable|string|max:255',
        'rating' => 'nullable|numeric|min:0|max:5',
        'password' => 'nullable|string|min:8',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Find the user
    $doc = User::findOrFail($id);

    // Update user fields
    $doc->name = $request->name;

    // Find the doctor profile (create one if it doesn't exist)
    $doctor = $doc->doctor ?? new Doctor(); // If doctor doesn't exist, create a new one

    // Retrieve the name from the user table and set it in the doctor's profile
    $doctor->name = $doc->name;  // Retrieve name from the user table and assign it

    // Update doctor details
    $doctor->phone = $request->phone;
    $doctor->specialization = $request->specialization;
    $doctor->room = $request->room;
    $doctor->appointment = $request->appointment;
    $doctor->rating = $request->rating;

    // Update password if provided
    if ($request->filled('password')) {
        $doc->password = Hash::make($request->password);
    }

    // Handle profile picture upload
    if ($request->hasFile('profile_picture')) {
        $file = $request->file('profile_picture');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/profile'), $filename);
        $doc->picture = $filename;
    }

    // Save the user and doctor data
    $doc->save();
    $doctor->user_id = $doc->id;  // Set the user_id before saving the doctor
    $doctor->save();

    // Redirect with success message
    return redirect()->back()->with('success', 'Profile updated successfully!');
}


   
      /**
       * Delete the user's profile.
       */
      public function deleteDoctorProfile($id)
      {
          // Check if session contains the user ID and ensure user ID matches the session
          if (!session()->has('user_id') || session('user_id') != $id) {
              return redirect()->route('login')->with('error', 'Unauthorized access.');
          }
   
          $doc = User::findOrFail($id);
   
          // Delete the user's profile picture if exists
          if ($doc->picture && file_exists(public_path('uploads/profile/' . $doc->picture))) {
              unlink(public_path('uploads/profile/' . $doc->picture));
          }
   
          $doc->delete();
   
          // Clear session data
          session()->flush();
   
          return redirect()->route('home')->with('success', 'Your profile has been deleted.');
      }



}