<x-header />

<!-- Register Section -->
<div class="page-section">
  <div class="container">
    <h1 class="text-center wow fadeInUp">Register</h1>

    <form class="contact-form mt-5" id="contactForm" method="POST" action="{{ route('registerUser') }}" enctype="multipart/form-data">
      @csrf  <!-- CSRF Token for security -->

      <!-- Name Input -->
      <div class="row mb-2"> <!-- Reduced margin-bottom -->
        <div class="col-12 py-2 wow fadeInUp">
          <label for="name">Name</label>
          <input type="text" id="name" class="form-control" name="name" placeholder="Name" required>
        </div>
      </div>

      <!-- Email Input -->
      <div class="row mb-2"> <!-- Reduced margin-bottom -->
        <div class="col-12 py-2 wow fadeInUp">
          <label for="email">Email</label>
          <input type="email" id="email" class="form-control" name="email" placeholder="Email" required>
        </div>
      </div>

      <!-- Password Input -->
      <div class="row mb-2"> <!-- Reduced margin-bottom -->
        <div class="col-12 py-2 wow fadeInUp">
          <label for="password">Password</label>
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
      <div class="row mb-2"> <!-- Reduced margin-bottom -->
        <div class="col-12 py-2 wow fadeInUp">
          <label for="confirmPassword">Confirm Password</label>
          <div class="position-relative">
            <input type="password" id="confirmPassword" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
            <!-- SVG image button for toggling password visibility -->
            <button type="button" class="btn toggle-btn" onclick="togglePasswordVisibility('confirmPassword', 'eyeIcon2')">
              <img id="eyeIcon2" src="../assets/img/eye.svg" alt="Toggle Password" class="eye-img">
            </button>
          </div>
        </div>
      </div>

      <!-- Error Messages -->
      <div id="passwordError" class="error-message" style="display: none;">Passwords do not match.</div>
      <div id="successMessage" class="success-message" style="display: none;">Registration successful!</div>

      <!-- File Upload Input -->
      <div class="row mb-2"> <!-- Reduced margin-bottom -->
        <div class="col-12 py-2 wow fadeInUp">
          <label for="file">Upload Profile Picture</label>
          <input type="file" id="file" class="form-control" name="file" accept="image/*" required>
          <img id="imagePreview" class="image-preview" src="" alt="Image Preview" style="display: none;">
        </div>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary wow zoomIn">Register</button>
    </form>
  </div>
</div>

<x-footer />

<script>
  // Toggle password visibility
  function togglePasswordVisibility(inputId, eyeIconId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById(eyeIconId);
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      eyeIcon.src = '../assets/img/eye1.svg'; // Switch to eye1.svg when password is visible
    } else {
      passwordInput.type = 'password';
      eyeIcon.src = '../assets/img/eye.svg'; // Switch back to eye.svg when password is hidden
    }
  }

  document.getElementById('contactForm').addEventListener('submit', function (event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const passwordError = document.getElementById('passwordError');
    const successMessage = document.getElementById('successMessage');

    if (password !== confirmPassword) {
      event.preventDefault();  // Prevent form submission
      passwordError.style.display = 'block';
      successMessage.style.display = 'none';
    } else {
      passwordError.style.display = 'none';
      successMessage.style.display = 'block';
    }
  });

  document.getElementById('file').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const reader = new FileReader();

    reader.onload = function(e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
    };

    if (file) {
      reader.readAsDataURL(file);
    } else {
      preview.src = "";
      preview.style.display = 'none';
    }
  });
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
  .contact-form input[type="text"],
  .contact-form input[type="email"],
  .contact-form input[type="password"],
  .contact-form input[type="file"] {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 8px;  /* Reduced padding for compactness */
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

  /* Error and success message styling */
  .error-message {
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
    display: none;
  }
  
  .success-message {
    color: green;
    font-size: 1em;
    margin-top: 10px;
    display: none;
  }

  /* Center the image preview */
  .image-preview {
    display: block;
    margin: 10px auto;
    width: 150px;
    height: 150px;
    border-radius: 8px;
    object-fit: cover;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
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
