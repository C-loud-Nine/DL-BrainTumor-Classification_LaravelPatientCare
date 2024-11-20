<x-header />

<div class="back-to-top"></div>

<div class="page-section">
    <div class="container">
        <h1 class="text-center wow fadeInUp">Confirm Your Identity</h1>

        <!-- Error Message -->
        <div id="error-message" class="alert alert-danger d-none">
            <p></p>
        </div>

        <!-- Success Message -->
        <div id="success-message" class="alert alert-success d-none">
            <p></p>
        </div>

        <!-- Always Show Name Input Form -->
        <form id="verify-form" class="contact-form mt-5">
            @csrf <!-- CSRF Token for security -->
            <div class="row mb-3">
                <div class="col-12 py-2 wow fadeInUp">
                    <label for="name">Enter Your Name</label>
                    <input type="text" id="name" class="form-control" name="name" placeholder="Name" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary wow zoomIn">Verify Name</button>
        </form>

        <!-- User Confirmation Section -->
        <div id="user-confirmation" class="text-center mt-5 d-none">
            <div class="text-center">
                <img id="user-picture" class="img-fluid rounded-circle" style="width: 150px; height: 150px; margin: 0 auto;" />
            </div>
            <h3 id="user-name" class="mt-3"></h3>
            <p id="user-email"></p>

            <a href="{{ route('forget-password') }}" class="btn btn-success mt-4">This is my account</a>
        </div>
    </div>
</div>

<x-footer />

<style>
    /* Styles remain the same */
    .contact-form {
        max-width: 500px;
        margin: 0 auto;
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .contact-form label {
        font-weight: bold;
        margin-top: 10px;
        display: block;
        color: #333;
    }

    .contact-form input {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px;
        width: 100%;
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

    .alert {
        margin-top: 10px;
        border-radius: 4px;
        padding: 10px;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .text-center img {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        margin-bottom: 20px;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
        color: white;
        padding: 12px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .btn-success:hover {
        background-color: #218838;
    }
</style>

<script>
    // JavaScript code to handle the AJAX form submission
    document.getElementById("verify-form").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent normal form submission

        let name = document.getElementById("name").value;
        let csrf_token = document.querySelector('input[name="_token"]').value;

        // Reset previous messages and user confirmation
        document.getElementById("error-message").classList.add("d-none");
        document.getElementById("success-message").classList.add("d-none");
        document.getElementById("user-confirmation").classList.add("d-none");

        // Make an AJAX request to verify the user
        fetch("{{ route('verifyUser') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrf_token
            },
            body: JSON.stringify({ name: name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // If user is verified, show confirmation
                document.getElementById("user-name").textContent = data.user.name;

                // Partially hide the email (show the first 3 characters, then *** before the domain)
                let email = data.user.email;
                let hiddenEmail = email.slice(0, 3) + "***" + email.slice(email.indexOf('@'));
                document.getElementById("user-email").textContent = "Email: " + hiddenEmail;

                // Update user picture using the asset helper
                document.getElementById("user-picture").src = "{{ asset('uploads/profile') }}/" + data.user.picture;
                document.getElementById("user-picture").style.display = 'block'; // Ensure the image is visible

                document.getElementById("user-confirmation").classList.remove("d-none");
                document.getElementById("success-message").classList.remove("d-none");
                document.getElementById("success-message").textContent = data.message;

                // Clear the input field
                document.getElementById("name").value = '';
            } else {
                // Show error message if user not found
                document.getElementById("error-message").classList.remove("d-none");
                document.getElementById("error-message").textContent = data.message;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            document.getElementById("error-message").classList.remove("d-none");
            document.getElementById("error-message").textContent = "Something went wrong, please try again.";
        });
    });
</script>
