<x-header />

<body class="d-flex flex-column min-vh-100">
    <main class="flex-grow-1">
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Reset Password</h4>
                        </div>

                        <!-- Display Errors -->
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

                            <!-- Reset Password Form -->
                            <form class="contact-form" method="POST" action="{{ route('forget.password.post') }}">
                                @csrf

                                <div class="mb-3">
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

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        Send Password Reset Link
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
        /* Custom footer styles */
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

        /* Main content styles */
        main {
            padding: 2rem 0;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        /* Success message styling */
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        /* Fix wow animation conflicts */
        .wow {
            visibility: visible !important;
        }
    </style>
</body>

<x-footer />
