<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Log;  



class ImageUploadController extends Controller
{
        
    
        public function usermri()
        {
            // Check if session contains the user ID and ensure user ID matches the session
            if (!session()->has('user_id')) {
                return redirect()->route('login')->with('error', 'Unauthorized access. Please log in.');
            }
            // Show the upload form without results initially
            return view('user.usermri', ['result' => null, 'imageUrl' => null]);
        }


        public function uploadAndPredict(Request $request)
        {
            // Check if the session is set (user is logged in)
            if (!session()->has('user_id')) {
                return redirect()->route('login')->with('error', 'Please log in to upload an MRI image.');
            }
        
            // Validate the uploaded image
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'image.required' => 'Please upload an image.',
                'image.image' => 'The file must be a valid image.',
                'image.mimes' => 'Only JPEG, PNG, and JPG formats are supported.',
                'image.max' => 'Image size must not exceed 2MB.',
            ]);
        
            // Handle file upload
            if ($request->hasFile('image')) {
                $image = $request->file('image'); // Retrieve the uploaded file
                $uploadPath = public_path('uploads/mri/'); // Define the destination path
        
                // Ensure the folder exists (create it if it doesn't)
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true); // Create the directory if it doesn't exist
                }
        
                // Use the original filename for the uploaded image
                $imageName = $image->getClientOriginalName();
        
                // If a file with the same name already exists, add a timestamp to the filename
                if (file_exists($uploadPath . $imageName)) {
                    $timestamp = time();
                    $imageName = $timestamp . '_' . $image->getClientOriginalName();
                }
        
                // Move the uploaded file to the destination folder
                $image->move($uploadPath, $imageName);
            }
        
            try {
                // Prepare the image for FastAPI prediction
                $imageData = fopen($uploadPath . $imageName, 'r');
                $response = Http::attach('file', $imageData, $imageName)
                    ->post(env('FASTAPI_URL_2') . '/predict');
        
                if ($response->successful()) {
                    $result = $response->json();
                    

                    if(isset($result['prediction'], $result['confidence'])){
                    // // Get session data
                    $scannerName = session('user_name');
                    $scannerId = session('user_id');
                    $userName = session('user_name');
                    $userId = session('user_id');
                    $userType = session('user_type');
                    $reportClass = $result['prediction'];
                    $confidence = $result['confidence'];
        
                    // Store the report in the database
                    Report::create([
                        'scanner_name' => $scannerName,
                        'scanner_id' => $scannerId,
                        'user_name' => $userName,
                        'user_id' => $userId,
                        'type' => $userType,
                        'report_class' => $reportClass,
                        'confidence' => $confidence,
                        'report_image' => $imageName, // Save only the image name
                    ]);

                }
        
                    // Redirect with results
                    return redirect()->route('usermri')->with([
                        'result' => $result,
                        'imageUrl' => asset('uploads/mri/' . $imageName), // Generate the full URL for the frontend
                    ]);
                }
        
                // Handle failed predictions
                return back()->withErrors(['message' => 'Prediction failed. Please try again.']);
            } catch (\Exception $e) {
                \Log::error('FastAPI Connection Error: ' . $e->getMessage());
                return back()->withErrors(['message' => 'Error connecting to FastAPI: ' . $e->getMessage()]);
            }
        }



        public function userreportlist()
        {
            // Get the user ID from the session
            $userId = session('user_id');
        
            // Retrieve all reports for the user
            $reports = Report::where('user_id', $userId)->get();
        
            // Return the view with the reports
            return view('user.userreportlist', ['reports' => $reports]);
        }


        public function deleteReport(Request $request)
        {
            try {
                // Validate that the report_id is passed and exists in the database
                $request->validate([
                    'report_id' => 'required|exists:reports,id',
                ]);

                // Find the report by ID
                $report = Report::findOrFail($request->report_id);

                // Delete the report record
                $report->delete();

                // Redirect back with success message
                return redirect()->route('userreportlist')->with('success', 'Report deleted successfully.');
            } catch (\Exception $e) {
                return back()->with('error', 'Error deleting the report: ' . $e->getMessage());
            }
        }



        public function doctormri()
        {
            // Show the upload form without results initially
            return view('user.doctormri', ['result' => null, 'imageUrl' => null]);
        }
        

        public function doctorScanReport(Request $request)
        {
            // Check if the session is set (user is logged in)
            if (!session()->has('user_id')) {
                return redirect()->route('login')->with('error', 'Please log in to upload an MRI image.');
            }
        
            // Validate the inputs (image, user_name, user_id)
            $request->validate([
                'user_name' => 'required|string|max:255',
                'user_id' => 'required|string|max:50',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'user_name.required' => 'User name is required.',
                'user_id.required' => 'User ID is required.',
                'image.required' => 'Please upload an image.',
                'image.image' => 'The file must be a valid image.',
                'image.mimes' => 'Only JPEG, PNG, and JPG formats are supported.',
                'image.max' => 'Image size must not exceed 2MB.',
            ]);
        
            // Handle file upload
            if ($request->hasFile('image')) {
                $image = $request->file('image'); // Retrieve the uploaded file
                $uploadPath = public_path('uploads/mri/'); // Define the destination path
        
                // Ensure the folder exists (create it if it doesn't)
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true); // Create the directory if it doesn't exist
                }
        
                // Use the original filename for the uploaded image
                $imageName = $image->getClientOriginalName();
        
                // If a file with the same name already exists, add a timestamp to the filename
                if (file_exists($uploadPath . $imageName)) {
                    $timestamp = time();
                    $imageName = $timestamp . '_' . $image->getClientOriginalName();
                }
        
                // Move the uploaded file to the destination folder
                $image->move($uploadPath, $imageName);
            }
        
            try {
                // Prepare the image for FastAPI prediction
                $imageData = fopen($uploadPath . $imageName, 'r');
                $response = Http::attach('file', $imageData, $imageName)
                    ->post(env('FASTAPI_URL') . '/predict');
        
                if ($response->successful()) {
                    $result = $response->json();
        
                    // Get session data
                    $scannerName = session('user_name'); // Name of the logged-in doctor
                    $scannerId = session('user_id');    // ID of the logged-in doctor
                    $scannerType = session('user_type');
        
                    // Retrieve form data (these values are explicitly from the form)
                    $userName = $request->input('user_name');
                    $userId = $request->input('user_id');
                    $reportClass = $result['prediction'];
                    $confidence = $result['confidence'];


                                    // Check if the user exists with the provided username and user ID
                    $user = User::where('id', $request->user_id)
                        ->where('name', $request->user_name)
                        ->first();

                    // If no such user exists, show an error message
                    if (!$user) {
                    return back()->with('error', 'The User Name and User ID do not match our records.');
                    }
        
                    // Store the report in the database
                    Report::create([
                        'scanner_name' => $scannerName,
                        'scanner_id' => $scannerId,
                        'user_name' => $userName, // Directly from the form
                        'user_id' => $userId,     // Directly from the form
                        'type' => $scannerType,
                        'report_class' => $reportClass,
                        'confidence' => $confidence,
                        'report_image' => $imageName, // Save only the image name
                    ]);
        
                    // Redirect with results
                    return redirect()->route('doctormri')->with([
                        'result' => $result,
                        'imageUrl' => asset('uploads/mri/' . $imageName), // Generate the full URL for the frontend
                    ]);
                }
        
                // Handle failed predictions
                return back()->withErrors(['message' => 'Prediction failed. Please try again.']);
            } catch (\Exception $e) {
                \Log::error('FastAPI Connection Error: ' . $e->getMessage());
                return back()->withErrors(['message' => 'Error connecting to FastAPI: ' . $e->getMessage()]);
            }
        }











        //////////////////



        public function usermri2()
        {
            // Show the upload form without results initially
            return view('user.usermri2', ['result' => null, 'imageUrl' => null]);
        }
        


        public function uploadAndPredict2(Request $request)
        {
            // Check if the session is set (user is logged in)
            if (!session()->has('user_id')) {
                return redirect()->route('login')->with('error', 'Please log in to upload an MRI image.');
            }
        
            // Validate the uploaded image
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'image.required' => 'Please upload an image.',
                'image.image' => 'The file must be a valid image.',
                'image.mimes' => 'Only JPEG, PNG, and JPG formats are supported.',
                'image.max' => 'Image size must not exceed 2MB.',
            ]);
        
            // Handle file upload
            if ($request->hasFile('image')) {
                $image = $request->file('image'); // Retrieve the uploaded file
                $uploadPath = public_path('uploads/mri/'); // Define the destination path
        
                // Ensure the folder exists (create it if it doesn't)
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true); // Create the directory if it doesn't exist
                }
        
                // Use the original filename for the uploaded image
                $imageName = $image->getClientOriginalName();
        
                // If a file with the same name already exists, add a timestamp to the filename
                if (file_exists($uploadPath . $imageName)) {
                    $timestamp = time();
                    $imageName = $timestamp . '_' . $image->getClientOriginalName();
                }
        
                // Move the uploaded file to the destination folder
                $image->move($uploadPath, $imageName);
            }
        
            try {
                // Prepare the image for FastAPI prediction
                $imageData = fopen($uploadPath . $imageName, 'r');
                $response = Http::attach('file', $imageData, $imageName)
                    ->post(env('FASTAPI_URL_2') . '/predict');
        
                if ($response->successful()) {
                    $result = $response->json();
        
                    // // Get session data
                    // $scannerName = session('user_name');
                    // $scannerId = session('user_id');
                    // $userName = session('user_name');
                    // $userId = session('user_id');
                    // $userType = session('user_type');
                    // $reportClass = $result['prediction'];
                    // $confidence = $result['confidence'];
        
                    // // Store the report in the database
                    // Report::create([
                    //     'scanner_name' => $scannerName,
                    //     'scanner_id' => $scannerId,
                    //     'user_name' => $userName,
                    //     'user_id' => $userId,
                    //     'type' => $userType,
                    //     'report_class' => $reportClass,
                    //     'confidence' => $confidence,
                    //     'report_image' => $imageName, // Save only the image name
                    // ]);
        
                    // Redirect with results
                    return redirect()->route('usermri2')->with([
                        'result' => $result,
                        'imageUrl' => asset('uploads/mri/' . $imageName), // Generate the full URL for the frontend
                    ]);
                }
        
                // Handle failed predictions
                return back()->withErrors(['message' => 'Prediction failed. Please try again.']);
            } catch (\Exception $e) {
                \Log::error('FastAPI Connection Error: ' . $e->getMessage());
                return back()->withErrors(['message' => 'Error connecting to FastAPI: ' . $e->getMessage()]);
            }
        }



////////////////////////////////////////////////////




public function forcefulTumorClassification(Request $request)
{
    try {
        // Retrieve the image path from the form submission
        $imageUrl = $request->input('imagePath');

        // Log the received image URL for debugging
        Log::debug('Received image URL: ' . $imageUrl);

        // Convert the URL to a local file path
        $imagePath = public_path('uploads/mri/' . basename($imageUrl)); // Correct path to check file existence

        // Log the converted file path for debugging
        Log::debug('Converted image file path: ' . $imagePath);

        // Validate the file existence
        if (!file_exists($imagePath)) {
            Log::debug('Image file does not exist: ' . $imagePath);
            return back()->withErrors(['message' => 'Image file does not exist.']);
        }

        // Check if the file is readable
        if (!is_readable($imagePath)) {
            Log::debug('Image file is not readable: ' . $imagePath);
            return back()->withErrors(['message' => 'Image file is not readable.']);
        }

        // Log that the file exists and is readable
        Log::debug('Image file exists and is readable: ' . $imagePath);

        // Open the image file for reading
        $imageData = fopen($imagePath, 'r');

        // Log the start of file reading
        Log::debug('Opening image file for reading: ' . $imagePath);

        // Send the image to FastAPI for prediction
        $response = Http::attach('file', $imageData, basename($imagePath))
            ->post(env('FASTAPI_URL') . '/predict');

        // Close the file after the request
        fclose($imageData);

        // Check if the response was successful
        if ($response->successful()) {
            $result = $response->json();

            // Log the prediction result
            Log::debug('Prediction successful: ' . json_encode($result));

            // Redirect with the prediction result and image URL
            return redirect()->route('usermri')->with([
                'result' => $result,
                'imageUrl' => asset('uploads/mri/' . basename($imagePath)), // Generate the full URL for the frontend
            ]);
        }

        // Handle failed predictions
        Log::debug('Prediction failed for image: ' . $imagePath);
        return back()->withErrors(['message' => 'Tumor classification failed. Please try again.']);
    } catch (\Exception $e) {
        // Log any exception message
        Log::error('Error connecting to FastAPI: ' . $e->getMessage());
        return back()->withErrors(['message' => 'Error connecting to FastAPI: ' . $e->getMessage()]);
    }
}




public function forcefulTumorClassification2(Request $request)
{
    try {
        // Retrieve the image path from the form submission
        $imageUrl = $request->input('imagePath');

        // Log the received image URL for debugging
        Log::debug('Received image URL: ' . $imageUrl);

        // Convert the URL to a local file path
        $imagePath = public_path('uploads/mri/' . basename($imageUrl)); // Correct path to check file existence

        // Log the converted file path for debugging
        Log::debug('Converted image file path: ' . $imagePath);

        // Validate the file existence
        if (!file_exists($imagePath)) {
            Log::debug('Image file does not exist: ' . $imagePath);
            return back()->withErrors(['message' => 'Image file does not exist.']);
        }

        // Check if the file is readable
        if (!is_readable($imagePath)) {
            Log::debug('Image file is not readable: ' . $imagePath);
            return back()->withErrors(['message' => 'Image file is not readable.']);
        }

        // Log that the file exists and is readable
        Log::debug('Image file exists and is readable: ' . $imagePath);

        // Open the image file for reading
        $imageData = fopen($imagePath, 'r');

        // Log the start of file reading
        Log::debug('Opening image file for reading: ' . $imagePath);

        // Send the image to FastAPI for prediction
        $response = Http::attach('file', $imageData, basename($imagePath))
            ->post(env('FASTAPI_URL') . '/predict');

        // Close the file after the request
        fclose($imageData);

        // Check if the response was successful
        if ($response->successful()) {
            $result = $response->json();

            // Log the prediction result
            Log::debug('Prediction successful: ' . json_encode($result));

            // Redirect with the prediction result and image URL
            return redirect()->route('usermri2')->with([
                'result' => $result,
                'imageUrl' => asset('uploads/mri/' . basename($imagePath)), // Generate the full URL for the frontend
            ]);
        }

        // Handle failed predictions
        Log::debug('Prediction failed for image: ' . $imagePath);
        return back()->withErrors(['message' => 'Tumor classification failed. Please try again.']);
    } catch (\Exception $e) {
        // Log any exception message
        Log::error('Error connecting to FastAPI: ' . $e->getMessage());
        return back()->withErrors(['message' => 'Error connecting to FastAPI: ' . $e->getMessage()]);
    }
}

////////////////////////////////////////////////



        public function usermri3()
        {
            // Show the upload form without results initially
            return view('user.usermri3', ['result' => null, 'imageUrl' => null]);
        }
        


        public function uploadAndPredict3(Request $request)
        {
            // Check if the session is set (user is logged in)
            if (!session()->has('user_id')) {
                return redirect()->route('login')->with('error', 'Please log in to upload an MRI image.');
            }
        
            // Validate the uploaded image
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'image.required' => 'Please upload an image.',
                'image.image' => 'The file must be a valid image.',
                'image.mimes' => 'Only JPEG, PNG, and JPG formats are supported.',
                'image.max' => 'Image size must not exceed 2MB.',
            ]);
        
            // Handle file upload
            if ($request->hasFile('image')) {
                $image = $request->file('image'); // Retrieve the uploaded file
                $uploadPath = public_path('uploads/mri/'); // Define the destination path
        
                // Ensure the folder exists (create it if it doesn't)
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true); // Create the directory if it doesn't exist
                }
        
                // Use the original filename for the uploaded image
                $imageName = $image->getClientOriginalName();
        
                // If a file with the same name already exists, add a timestamp to the filename
                if (file_exists($uploadPath . $imageName)) {
                    $timestamp = time();
                    $imageName = $timestamp . '_' . $image->getClientOriginalName();
                }
        
                // Move the uploaded file to the destination folder
                $image->move($uploadPath, $imageName);
            }
        
            try {
                // Prepare the image for FastAPI prediction
                $imageData = fopen($uploadPath . $imageName, 'r');
                $response1 = Http::attach('file', $imageData, $imageName)
                    ->post(env('FASTAPI_URL') . '/predict');
                $response2 = Http::attach('file', $imageData, $imageName)
                    ->post(env('FASTAPI_URL_2') . '/predict');
        
                if ($response1->successful()) {
                    $result1 = $response1->json();
                    $result2 = $response2->json();
        
                    // // Get session data
                    // $scannerName = session('user_name');
                    // $scannerId = session('user_id');
                    // $userName = session('user_name');
                    // $userId = session('user_id');
                    // $userType = session('user_type');
                    // $reportClass = $result['prediction'];
                    // $confidence = $result['confidence'];
        
                    // // Store the report in the database
                    // Report::create([
                    //     'scanner_name' => $scannerName,
                    //     'scanner_id' => $scannerId,
                    //     'user_name' => $userName,
                    //     'user_id' => $userId,
                    //     'type' => $userType,
                    //     'report_class' => $reportClass,
                    //     'confidence' => $confidence,
                    //     'report_image' => $imageName, // Save only the image name
                    // ]);
        
                    // Redirect with results
                    return redirect()->route('usermri3')->with([
                        'result1' => $result1,
                        'result2' => $result2,
                        'imageUrl' => asset('uploads/mri/' . $imageName), // Generate the full URL for the frontend
                    ]);
                }
        
                // Handle failed predictions
                return back()->withErrors(['message' => 'Prediction failed. Please try again.']);
            } catch (\Exception $e) {
                \Log::error('FastAPI Connection Error: ' . $e->getMessage());
                return back()->withErrors(['message' => 'Error connecting to FastAPI: ' . $e->getMessage()]);
            }
        }



}