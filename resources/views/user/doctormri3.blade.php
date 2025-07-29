<!DOCTYPE html>
<html lang="en">
<head>
    <x-header />
</head>
<body>
    <div class="container11">
        <div class="text-center mb-5">
            <h1 class="text-primary fw-bold">MRI Prediction 3 (Doctor's Panel)</h1>
            <p class="text-muted">Upload an MRI image and provide patient details for dual-model analysis.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger custom-error-message text-center">
                <p class="error-text">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Upload Form with Patient Details -->
        <div class="card p-5 shadow-lg mb-4">
            <form action="{{ route('doctorScanReport3') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Patient Details Row -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="user_name" class="form-label fw-bold">Patient Name</label>
                        <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter Patient Name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="user_id" class="form-label fw-bold">Patient ID</label>
                        <input type="text" name="user_id" id="user_id" class="form-control" placeholder="Enter Patient ID" required>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label for="image" class="form-label fw-bold">Upload MRI Image</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Analyze with Both Models</button>
                </div>
            </form>
        </div>

        @if(session('result1') && session('result2') && session('imageUrl'))
            <div class="result-section text-center mt-5 mb-5">
                <h2 class="text-success fw-bold">Dual-Model Analysis Results</h2>
                <img src="{{ session('imageUrl') }}" alt="Uploaded Image" class="image-preview my-4">
                
                @if(session('result1')['is_mri'] && session('result2')['is_mri'])
                    <!-- Results Comparison Section -->
                    <div class="results-container">
                        <!-- Model 1 Results -->
                        <div class="result-block mt-4">
                            <h3 class="text-primary fw-bold">Primary Model</h3>
                            <p class="fs-3 mb-3">
                                <strong class="text-dark">Diagnosis:</strong>
                                <span class="text-primary fs-4">{{ session('result1')['prediction'] }}</span>
                            </p>
                            <p class="fs-3 mb-3">
                                <strong class="text-dark">Confidence:</strong>
                                <span class="text-warning fs-4">{{ number_format(session('result1')['confidence'] * 100, 2) }}%</span>
                            </p>
                        </div>

                        <!-- Model 2 Results -->
                        <div class="result-block mt-4">
                            <h3 class="text-primary fw-bold">Secondary Model</h3>
                            <p class="fs-3 mb-3">
                                <strong class="text-dark">Diagnosis:</strong>
                                <span class="text-primary fs-4">{{ session('result2')['prediction'] }}</span>
                            </p>
                            <p class="fs-3 mb-3">
                                <strong class="text-dark">Confidence:</strong>
                                <span class="text-warning fs-4">{{ number_format(session('result2')['confidence'] * 100, 2) }}%</span>
                            </p>
                        </div>
                    </div>

                @else
                    <!-- Non-MRI Warning -->
                    <div class="non-mri-block mt-4">
                        <h3 class="text-danger fw-bold">Non-MRI Image Detected</h3>
                        <p class="fs-3 mb-3 text-muted">One or both models classified this as a non-MRI image.</p>
                        <p class="fs-3 mb-3 text-muted">For accurate diagnosis, please upload a valid MRI scan.</p>
                        
                        <form action="{{ route('forceful.mritumor3') }}" method="POST">
                            @csrf
                            <input type="hidden" name="imagePath" value="{{ session('imageUrl') }}">
                            <input type="hidden" name="user_name" value="{{ old('user_name') }}">
                            <input type="hidden" name="user_id" value="{{ old('user_id') }}">
                            <button type="submit" class="btn btn-danger">Proceed with Analysis Anyway</button>
                        </form>
                    </div>
                @endif
            </div>
        @endif

        @if(session('proceed_disclaimer'))
            <div class="alert alert-warning text-center mt-4">
                <p class="fw-bold">
                    <i class="bi bi-exclamation-triangle-fill"></i> Clinical Notice: This image was flagged as potentially non-MRI. 
                    Proceeding with analysis may yield unreliable results. Always verify scan quality.
                </p>
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
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>
</html>

<style>
    /* General Body Styling */
    body {
        background: linear-gradient(to bottom, #f8f9fa, #ffffff);
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
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
    }

    /* Results Container */
    .results-container {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
        margin-top: 40px;
    }

    /* Result Block Styling */
    .result-block {
        flex: 1;
        min-width: 300px;
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 25px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .result-block:hover {
        transform: translateY(-5px);
    }

    /* Image Preview Styling */
    .image-preview {
        max-width: 400px;
        max-height: 400px;
        object-fit: contain;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Button Styling */
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 10px 25px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0069d9;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 123, 255, 0.25);
    }

    /* Headings Styling */
    h1, h2 {
        font-weight: 700;
        line-height: 1.3;
    }

    h1 {
        font-size: 2.2rem;
    }

    h2 {
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
    }

    h3 {
        font-size: 1.4rem;
        margin-bottom: 1rem;
    }

    /* Text Styling */
    .text-muted {
        font-size: 1rem;
        color: #6c757d !important;
    }

    /* Result Text Styling */
    .result-text p {
        font-size: 1.3rem;
        margin: 12px 0;
    }

    .text-dark {
        color: #212529;
    }

    .text-primary {
        color: #007bff;
        font-weight: 600;
    }

    .text-warning {
        color: #dc3545;
        font-weight: 600;
    }

    /* Form Elements */
    .form-control {
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #ced4da;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
    }

    /* Doctor Notes Section */
    .doctor-notes {
        max-width: 800px;
        margin: 40px auto 0;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .doctor-notes textarea {
        min-height: 100px;
    }

    /* Non-MRI Block Styling */
    .non-mri-block {
        padding: 25px;
        background: #fff3f3;
        border: 1px solid #ffcccc;
        border-radius: 10px;
        margin: 30px auto;
        max-width: 700px;
        text-align: center;
    }

    /* Alert Styling */
    .alert {
        border-radius: 10px;
        padding: 20px;
    }

    .custom-error-message {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
    }

    .error-text {
        font-size: 1.2rem;
        font-weight: 600;
        color: #721c24;
        margin-bottom: 10px;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .container11 {
            padding: 30px 15px;
        }
        
        .results-container {
            flex-direction: column;
            gap: 20px;
        }
        
        .result-block {
            min-width: 100%;
        }
    }
</style>