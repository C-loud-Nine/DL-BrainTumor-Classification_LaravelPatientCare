<x-header />

<!-- Back to top button -->
<div class="back-to-top"></div>

<div class="page-section">
    <div class="container">
        <h1 class="text-center wow fadeInUp">Log In</h1>

        <!-- Incorrect Login Message -->
        @if(Session::has('error'))
            <div class="alert alert-danger">
                <p>{{ Session::get('error') }}</p>
            </div>
        @endif

        <form class="contact-form mt-5" method="POST" action="{{ route('loginUser') }}">
            @csrf  <!-- CSRF Token for security -->

            <div class="row mb-3">
                <div class="col-12 py-2 wow fadeInUp">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="col-12 py-2 wow fadeInUp">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary wow zoomIn">Login</button>
        </form>
    </div>
</div>

<x-footer />

<style>
    /* Center the form */
    .contact-form {
        max-width: 500px;
        margin: 0 auto;
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Customize input fields */
    .contact-form input[type="email"],
    .contact-form input[type="password"] {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px;
        width: 100%;
    }

    /* Submit button hover effect */
    .contact-form button {
        width: 100%;
        font-size: 16px;
        padding: 12px;
        border-radius: 4px;
        transition: background-color 0.3s;
    }
    .contact-form button:hover {
        background-color: #0056b3;
        color: #fff;
    }

    /* Form label styling */
    .contact-form label {
        font-weight: bold;
        margin-top: 10px;
        display: block;
        color: #333;
    }

    /* Alert styling */
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
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
