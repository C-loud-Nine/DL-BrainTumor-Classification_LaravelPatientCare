<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\RepVerdict;


class HomeController extends Controller
{
  
    

    public function index()
    {
        // Retrieve users of type 'doctor' with their associated doctor information
        $doctors = User::with('doctor')
            ->where('type', 'doctor')
            ->get();
        $doctor = Doctor::all();
        // Pass the data to the view
        return view('user.home', compact('doctors', 'doctor'));
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



    


public function doctorprofile()
{
    // Ensure the user is logged in
    if (!session()->has('user_id')) {
        return redirect()->route('login')->with('error', 'Please log in to view your profile.');
    }

    // Fetch the logged-in user's data
    $doc = User::with('doctor')->find(session('user_id'));

    // Get the list of specializations from the database
    $specializations = Specialization::all();

    return view('user.doctorprofile', compact('doc', 'specializations'));
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


// Fetch and display all doctors
public function doctorinfo()
{
    // Retrieve users of type 'doctor' with their associated doctor information
    $doctors = User::with('doctor')->where('type', 'doctor')->get();

    // Pass to the view
    return view('user.doctorinfo', compact('doctors'));
}

public function fetchDoctor($id)
{

    // Check if session contains the user ID and ensure user ID matches the session
    if (!session()->has('user_id')) {
        return redirect()->route('login')->with('error', 'Unauthorized access. Please log in.');
    }
    // Find the doctor by user ID and load associated doctor information
    $doctor = User::with('doctor')->where('type', 'doctor')->find($id);

    if (!$doctor || !$doctor->doctor) {
        return response()->json(['error' => 'Doctor not found'], 404);
    }

    // Return doctor details as JSON
    return response()->json([
        'name' => $doctor->doctor->name ?? 'N/A',
        'specialization' => $doctor->doctor->specialization ?? 'N/A',
        'phone' => $doctor->doctor->phone ?? 'N/A',
        'rating' => $doctor->doctor->rating ?? 0,
        'rating_count' => $doctor->doctor->rating_count ?? 0,
    ]);
}


public function rateDoctor(Request $request)
{
    \Log::info('Incoming Request:', $request->all());

    try {
        

        // Validate the request
        $request->validate([
            'doctor_id' => 'required|exists:doctors,user_id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        \Log::info('Validation passed.');

        // Find the doctor using user_id
        $doctor = Doctor::where('user_id', $request->doctor_id)->first();

        if (!$doctor) {
            \Log::error("Doctor with user_id {$request->doctor_id} not found.");
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        // Calculate the new rating
        $totalRatings = $doctor->rating_count ?? 0;
        $currentRating = $doctor->rating ?? 0;

        $newTotalRatings = $totalRatings + 1;
        $newRating = (($currentRating * $totalRatings) + $request->rating) / $newTotalRatings;

        \Log::info("New rating calculated for Doctor with user_id {$request->doctor_id}: $newRating");

        // Update doctor record
        $doctor->rating = $newRating;
        $doctor->rating_count = $newTotalRatings;
        $doctor->save();

        \Log::info("Doctor with user_id {$request->doctor_id} updated successfully.");

        // Return success response
        return response()->json(['message' => 'Rating submitted successfully'], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed:', $e->errors());
        return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
    } catch (ModelNotFoundException $e) {
        \Log::error('Doctor not found: ' . $e->getMessage());
        return response()->json(['error' => 'Doctor not found'], 404);
    } catch (\Exception $e) {
        \Log::error('Unexpected error:', ['message' => $e->getMessage()]);
        return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
    }
}






            
public function appointmentpage()
{
    return view('user.appointment');
}



      public function appointment(Request $request){
            $data = new appointment;
            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->doctor = $request->doctor;
            $data->date = $request->date;
            $data->message = $request->message;
            $data->status = 'pending';
            if(session()->has('user_id')){
                $data->user_id = session('user_id');
            }
            $data->save();
            return redirect()->back()->with('success', 'Your appointment has been submitted successfully. Please wait for your confirmation.!');
      }



      public function searchDoctors(Request $request)
{
    $term = $request->input('term');

    if (!$term) {
        return response()->json([]);
    }

    // Fetch doctors matching the search term
    $doctors = Doctor::where('name', 'LIKE', "%{$term}%")
        ->orWhere('specialization', 'LIKE', "%{$term}%")
        ->get(['name', 'specialization']);

    return response()->json($doctors);
}


public function getAllDoctors() {
    $doctors = Doctor::all(); // Assuming you have a Doctor model
    return response()->json($doctors);
}



public function userAppointments(Request $request)
{
    // Retrieve user_id from session
    $userId = $request->session()->get('user_id');

    // Fetch pending and rejected appointments
    $pendingRejected = Appointment::where('user_id', $userId)
        ->whereIn('status', ['pending', 'rejected'])
        ->get();

    // Fetch approved appointments
    $approved = Appointment::where('user_id', $userId)
        ->where('status', 'approved')
        ->get();

    $confirmed = Appointment::where('user_id', $userId)
        ->where('status', 'confirmed')
        ->get();

    // Return view with the data
    return view('user.userapp', compact('pendingRejected', 'approved', 'confirmed'));
}

public function confirmAppointment(Request $request, $id)
{

        $appointment = Appointment::find($id);
        $appointment->status = 'confirmed';
        $appointment->save();

        return redirect()->route('userapp')->with('success', 'Appointment confirmed successfully!');
 


return redirect()->route('userapp')->with('error', 'Appointment not found or access denied.');

}




public function rejectAppointment(Request $request, $id)
{
 
        $appointment = Appointment::findOrFail($id);

   
        $appointment->delete();

        return redirect()->route('userapp')->with('success', 'Appointment rejected successfully!');
  

  

    return redirect()->route('userapp')->with('error', 'Appointment not found or access denied.');
}


public function showAppointments()
{
    // Retrieve the doctor's name from the session
    $doctorName = session('user_name');

    if (!$doctorName) {
        return redirect()->route('login')->with('error', 'Doctor not found');
    }

    // Get appointments for the doctor based on the name stored in the session
    $appointments = Appointment::where('doctor', $doctorName)  // Use the 'doctor' column directly
        ->whereIn('status', ['pending', 'confirmed'])  // Correct filter for pending and confirmed status
        ->orderBy('date', 'asc')  // Order by appointment date
        ->get()
        ->groupBy(function ($appointment) {
            // Ensure the 'date' field is in a valid format
            return Carbon::parse($appointment->date)->format('Y-m-d');
        });

    return view('user.doctorapplist', compact('doctorName', 'appointments'));
}


public function showReports()
{
    // Retrieve the doctor's name (username) from the session
    $doctorName = session('user_name');  // Assuming the doctor's name is stored in session

    // Fetch all reports and order them by the creation timestamp (latest first)
    $reports = Report::orderBy('created_at', 'desc')->get();

    $ver = RepVerdict::all();

    // Get unique scanner names for filtering purposes
    $uniqueScannerNames = Report::distinct('scanner_name')->pluck('scanner_name');

    // Return the view with the necessary data
    return view('user.docreport', compact('reports', 'uniqueScannerNames', 'doctorName' , 'ver'));
}



 


public function generateReport($id)
{
    // Find the report by ID
    $report = Report::findOrFail($id);

    // Calculate the follow-up date (2 months after the report date)
    $follow_up_date = $report->created_at->addMonths(2)->format('d-m-Y'); // You can format it as you wish

    // Prepare the data to be passed to the view
    $data = [
        'user_name' => $report->user_name,
        'scanner_name' => $report->scanner_name ?? 'Not Assigned',
        'report_class' => $report->report_class,
        'confidence' => number_format($report->confidence * 100, 2),
        'created_at' => $report->created_at->format('d-m-Y H:i'),
        'report_date' => $report->created_at->format('l, d F Y H:i:s'),
        'assessment' => 'The patient’s MRI scan reveals significant findings indicating potential abnormalities. Further evaluation and follow-up are advised.',
        'suggestions' => 'We recommend additional diagnostic tests to confirm the findings. A follow-up consultation with the assigned physician is crucial for treatment planning.',
        'follow_up_date' => $follow_up_date, // Add the follow-up date to the data
        'report_image' => $report->report_image ? public_path('uploads/mri/'.$report->report_image) : null, // Use public_path for file path
    ];

    // Generate the PDF using the view with the data
    $pdf = Pdf::loadView('pdf.report', $data);

    // Return the generated PDF as a download
    return $pdf->download('MRI_Report_' . $report->id . '.pdf');
}


public function saveVerdict(Request $request)
{
    try {
        // Validate the incoming request
        $validated = $request->validate([
            'report_id' => 'required|exists:reports,id',
            'verdict' => 'required|in:Yes,No',
        ]);

        // Save verdict in the `repverdict` table
        RepVerdict::updateOrCreate(
            ['report_id' => $validated['report_id']], // Check for existing record
            ['verdict' => $validated['verdict']] // Update or create the verdict
        );

        // Return a success message (you can handle this differently if needed)
        return back()->with('success', 'Verdict saved successfully!');
    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error('Error saving verdict: ' . $e->getMessage());

        // Return a generic error message
        return back()->with('error', 'An error occurred while saving the verdict. Please try again.');
    }
}




}