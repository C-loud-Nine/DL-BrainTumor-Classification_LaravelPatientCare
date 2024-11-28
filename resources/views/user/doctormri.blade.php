<!DOCTYPE html>
<html lang="en">
<head>
    <x-header />
</head>
<body>
    <div class="container11">
        <div class="text-center mb-5">
            <h1 class="text-primary fw-bold">MRI Prediction (Doctor's Panel)</h1>
            <p class="text-muted">Upload an MRI image and provide user details for accurate medical analysis.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger custom-error-message text-center">
                <p class="error-text">{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('doctorScanReport') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- User Name and User ID in the same row -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="user_name" class="form-label fw-bold">User Name</label>
                    <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter User Name" required>
                </div>
                <div class="col-md-6">
                    <label for="user_id" class="form-label fw-bold">User ID</label>
                    <input type="text" name="user_id" id="user_id" class="form-control" placeholder="Enter User ID" required>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="mb-4">
                <label for="image" class="form-label fw-bold">Upload MRI Image</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">Predict</button>
            </div>
        </form>

        <!-- Prediction Result Section -->
        @if(session('result') && session('imageUrl'))
        <div class="result-section text-center mt-5 mb-5">
            <h2 class="text-success fw-bold">Prediction Result</h2>
            <img src="{{ session('imageUrl') }}" alt="Uploaded Image" class="image-preview my-4">
            <div class="result-text mt-4">
                <p class="fs-3 mb-3"><strong class="text-dark">Class:</strong> <span class="text-primary fs-4">{{ session('result')['prediction'] }}</span></p>
                <p class="fs-3 mb-3"><strong class="text-dark">Confidence:</strong> <span class="text-warning fs-4">{{ number_format(session('result')['confidence'] * 100, 2) }}%</span></p>
            </div>
        </div>
        @endif

        <!-- Errors Section -->
        @if($errors->any())
        <div class="alert alert-danger mt-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif
    </div>

    <x-footer />
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* General Body Styling */
        body {
            background: linear-gradient(to bottom, #eef2f3, #ffffff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Container Styling */
        .container11 {
            margin-top: 30px;
            margin-bottom: 50px;
            padding: 50px;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Result Section */
        .result-section {
            margin-top: 40px;
            margin-bottom: 40px;
            padding: 25px;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Image Preview Styling */
        .image-preview {
            max-width: 350px;
            max-height: 350px;
            object-fit: cover;
            border: 2px solid #dee2e6;
            border-radius: 10px;
        }

        /* Button Styling */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        /* Headings and Text Styling */
        h1, h2 {
            font-weight: 700;
            line-height: 1.4;
        }

        .text-muted {
            font-size: 0.9rem;
        }

        /* Result Text Styling */
        .result-text p {
            font-size: 1.4rem;
            margin: 10px 0;
        }

        .text-dark {
            color: #212529;
        }

        .text-primary {
            color: #007bff;
            font-weight: 600;
        }

        .text-warning {
            color: #b22234;
            font-weight: 600;
        }

        /* Form Padding */
        .card p {
            margin: 0;
        }

        /* Custom Error Message Styling */
        .custom-error-message {
            background-color: #f8d7da; /* Light red background */
            border: 1px solid #f5c6cb;  /* Soft red border */
            border-radius: 10px;  /* Rounded corners */
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);  /* Soft shadow for depth */
            margin-bottom: 20px; /* Space at the bottom */
        }

        .error-text {
            font-size: 1.2rem;
            font-weight: 600;
            color: black; /* Darker red text for better visibility */
            margin-bottom: 10px;
        }

        .btn-link {
            font-size: 1.5rem;
            font-weight: 800;
            color: #007bff;
            text-decoration: bold;
            transition: color 0.3s ease;
        }

        .btn-link:hover {
            color: #0056b3; /* Darker shade of blue for hover effect */
            text-decoration: underline;
        }

        /* Footer Margin */
        x-footer {
            margin-top: 40px;
        }
    </style>
</body>
</html>
