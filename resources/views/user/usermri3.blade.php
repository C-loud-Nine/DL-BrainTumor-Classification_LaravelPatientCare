<!-- resources/views/user/usermri3.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <x-header />
</head>
<body>
    <div class="container11">
        <div class="text-center mb-5">
            <h1 class="text-primary fw-bold">MRI Prediction 3</h1>
            <p class="text-muted">Upload an MRI image for accurate medical analysis.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger custom-error-message text-center">
                <p class="error-text">{{ session('error') }}</p>
                <a href="{{ route('login') }}" class="btn btn-link text-danger">Log in</a> to proceed.
            </div>
        @endif

        <!-- Upload Form -->
        <div class="card p-5 shadow-lg mb-4">
            <form action="{{ route('upload.predict3') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="image" class="form-label fw-bold">Upload MRI Image</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Predict</button>
                </div>
            </form>
        </div>

        @if(session('result1') && session('result2') && session('imageUrl'))
            <div class="result-section text-center mt-5 mb-5">
                <h2 class="text-success fw-bold">Prediction Results</h2>
                <img src="{{ session('imageUrl') }}" alt="Uploaded Image" class="image-preview my-4">
                
                @if(session('result1')['is_mri'] && session('result2')['is_mri'])
                    <!-- Results Section: Displaying predictions side by side -->
                    <div class="results-container">
                        <!-- Model 1 Prediction Result -->
                        <div class="result-block mt-4">
                            <h3 class="text-primary fw-bold">Model 1 Prediction</h3>
                            <p class="fs-3 mb-3">
                                <strong class="text-dark">Class:</strong>
                                <span class="text-primary fs-4">{{ session('result1')['prediction'] }}</span>
                            </p>
                            <p class="fs-3 mb-3">
                                <strong class="text-dark">Confidence:</strong>
                                <span class="text-warning fs-4">{{ session('result1')['confidence'] }}</span>
                            </p>
                        </div>

                        <!-- Model 2 Prediction Result -->
                        <div class="result-block mt-4">
                            <h3 class="text-primary fw-bold">Model 2 Prediction</h3>
                            <p class="fs-3 mb-3">
                                <strong class="text-dark">Class:</strong>
                                <span class="text-primary fs-4">{{ session('result2')['prediction'] }}</span>
                            </p>
                            <p class="fs-3 mb-3">
                                <strong class="text-dark">Confidence:</strong>
                                <span class="text-warning fs-4">{{ session('result2')['confidence'] }}</span>
                            </p>
                        </div>
                    </div>
                @else
                    <!-- Non-MRI Image Handling -->
                    <div class="non-mri-block mt-4">
                        <h3 class="text-danger fw-bold">Non-MRI Image Detected</h3>
                        <p class="fs-3 mb-3 text-muted">The uploaded image was classified as a non-MRI image by one or both models.</p>
                        <p class="fs-3 mb-3 text-muted">Please upload a valid MRI image for accurate predictions.</p>
                        
                        <!-- Optional: Forceful Tumor Classification -->
                        <form action="{{ route('forceful.mritumor3') }}" method="POST">
                            @csrf
                            <input type="hidden" name="imagePath" value="{{ session('imageUrl') }}">
                            <button type="submit" class="btn btn-danger">Proceed to Tumor Classification</button>
                        </form>
                    </div>
                @endif
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

    @include('admin.script')
</body>
</html>

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

    /* Results Container */
    .results-container {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-top: 30px;
    }

    /* Result Block Styling */
    .result-block {
        flex: 1;
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        text-align: center;
        min-width: 150px;
        margin: 50px;
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

    /* Text and Headings Styling */
    h1, h2 {
        font-weight: 700;
        line-height: 1.4;
    }

    .text-muted {
        font-size: 0.9rem;
    }

    /* Result Text Styling */
    .result-text p {
        font-size: 1.2rem;
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

    /* Custom Error Message Styling */
    .custom-error-message {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .error-text {
        font-size: 1.2rem;
        font-weight: 600;
        color: black;
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
        color: #0056b3;
        text-decoration: underline;
    }

    /* Non-MRI Block Styling */
    .non-mri-block {
        padding: 20px;
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 10px;
        margin-top: 30px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Footer Margin */
    x-footer {
        margin-top: 40px;
    }
</style>
