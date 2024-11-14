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
                    <div class="position-relative">
                        <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
                        <!-- SVG image button for toggling password visibility -->
                        <button type="button" class="btn toggle-btn" onclick="togglePasswordVisibility()">
                            <img id="eyeIcon" src="../assets/img/eye.svg" alt="Toggle Password" class="eye-img">
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary wow zoomIn">Login</button>
        </form>
    </div>
</div>

<x-footer />

<script>
  // Toggle password visibility
  function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      eyeIcon.src = '../assets/img/eye1.svg'; // Switch to eye1.svg when password is visible
    } else {
      passwordInput.type = 'password';
      eyeIcon.src = '../assets/img/eye.svg'; // Switch back to eye.svg when password is hidden
    }
  }
</script>

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

    /* Button styling for password toggle */
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

    /* Eye SVG styling */
    .eye-img {
        width: 20px;
        height: 20px;
    }

    /* Submit button hover effect */
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
