<x-header />

<body class="d-flex flex-column min-vh-100">
    <main class="flex-grow-1">
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0 text-center">Reset Password</h4>
                        </div>

                        <!-- Display Validation Errors -->
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="card-body">
                            <!-- Success Message -->
                            @if(session('status'))
                                <div class="alert alert-success">
                                    <strong>Success!</strong> {{ session('status') }}
                                </div>
                            @endif

                            <!-- Error Message -->
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    <strong>Error!</strong> {{ session('error') }}
                                </div>
                            @endif

                            <!-- Password Reset Form -->
                            <form method="POST" action="{{ route('reset.password.post') }}" class="contact-form mt-5">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="mb-3 wow fadeInUp">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" 
                                           id="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Enter your email address" 
                                           required>
                                    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                                <!-- Password Input -->
                        <div class="row mb-3">
                            <div class="col-12 py-2 wow fadeInUp">
                            <label for="password">Enter New Password</label>
                            <div class="position-relative">
                                <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
                                <!-- SVG image button for toggling password visibility -->
                                <button type="button" class="btn toggle-btn" onclick="togglePasswordVisibility('password', 'eyeIcon1')">
                                <img id="eyeIcon1" src="../assets/img/eye.svg" alt="Toggle Password" class="eye-img">
                                </button>
                            </div>
                            </div>
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="row mb-3">
                            <div class="col-12 py-2 wow fadeInUp">
                            <label for="confirmPassword">Confirm New Password</label>
                            <div class="position-relative">
                                <input type="password" id="confirmPassword" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                                <!-- SVG image button for toggling password visibility -->
                                <button type="button" class="btn toggle-btn" onclick="togglePasswordVisibility('confirmPassword', 'eyeIcon2')">
                                <img id="eyeIcon2" src="../assets/img/eye.svg" alt="Toggle Password" class="eye-img">
                                </button>
                            </div>
                            </div>
                        </div>

                                <div class="d-grid wow fadeInUp">
                                    <button type="submit" class="btn btn-primary zoomIn">
                                        Reset Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        footer {
            padding: 1rem 0 !important;
            margin-top: auto !important;
            font-size: 0.9rem !important;
        }

        footer .container {
            max-width: 100% !important;
        }

        footer p {
            margin-bottom: 0.5rem !important;
        }

        main {
            padding: 2rem 0;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .wow {
            visibility: visible !important;
        }

        .contact-form {
            max-width: 500px;
            margin: 0 auto;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .contact-form input[type="email"],
        .contact-form input[type="password"] {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            width: 100%;
        }

        .position-relative {
            position: relative;
        }

        .toggle-btn {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .toggle-btn:focus {
            outline: none;
        }

        .eye-img {
            width: 20px;
            height: 20px;
        }

        .contact-form button[type="submit"] {
            width: 100%;
            font-size: 16px;
            padding: 12px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .contact-form button[type="submit"]:hover {
            background-color: #0056b3;
            color: #fff;
        }

        .contact-form label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
            color: #333;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .contact-form {
                padding: 15px;
            }
        }
    </style>

    <script>
        // Toggle Password Visibility Function
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            eyeIcon.src = type === 'text' ? '../assets/img/eye1.svg' : '../assets/img/eye.svg';
        }
    </script>
</body>

<x-footer />
