<x-header />

<!-- Register Section -->
<div class="page-section">
  <div class="container">
    <h1 class="text-center wow fadeInUp">Register</h1>
    <form class="contact-form mt-5" id="contactForm" method="POST" action="{{ route('registerUser') }}" enctype="multipart/form-data">
      @csrf  <!-- CSRF Token for security -->

      <!-- Name Input -->
      <label for="name">Name</label>
      <input type="text" id="name" class="form-control" name="name" placeholder="Name" required>

      <!-- Email Input -->
      <label for="email">Email</label>
      <input type="email" id="email" class="form-control" name="email" placeholder="Email" required>

      <!-- Password Input -->
      <label for="password">Password</label>
      <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>

      <!-- Confirm Password Input -->
      <label for="confirmPassword">Confirm Password</label>
      <input type="password" id="confirmPassword" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
      
      <!-- Error Messages -->
      <div id="passwordError" class="error-message" style="display: none;">Passwords do not match.</div>
      <div id="successMessage" class="success-message" style="display: none;">Registration successful!</div>
      
      <!-- File Upload Input -->
      <label for="file">Upload Image</label>
      <input type="file" id="file" class="form-control" name="file" accept="image/*" required>
      <img id="imagePreview" class="image-preview" src="" alt="Image Preview" style="display: none;">

      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary wow zoomIn">Register</button>
    </form>
  </div>
</div>

<script>
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

    /* Custom input fields */
    .contact-form input[type="text"],
    .contact-form input[type="email"],
    .contact-form input[type="password"],
    .contact-form input[type="file"] {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 10px;
      width: 100%;
      margin-top: 5px;
    }

    /* Button styling */
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
</style>
