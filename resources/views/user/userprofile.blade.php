<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="copyright" content="MACode ID, https://macodeid.com/">
  <title>One Health - Profile</title>
  <link rel="stylesheet" href="../assets/css/maicons.css">
  <link rel="stylesheet" href="../assets/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/vendor/owl-carousel/css/owl.carousel.css">
  <link rel="stylesheet" href="../assets/vendor/animate/animate.css">
  <link rel="stylesheet" href="../assets/css/theme.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMz6N1zBc5i7VgrWxk+nE2k7v4KLKekf5vhT9gO" crossorigin="anonymous">
  <style>
    /* General page styles */
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f7fa;
      color: #333;
    }

    /* Profile page container */
    .profile-container {
      max-width: 900px;
      margin: 30px auto;
      padding: 25px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      border: 1px solid #ddd;
    }

    /* Profile header styling */
    .profile-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .profile-header h1 {
      font-size: 2.5rem;
      font-weight: bold;
      color: #007bff;
    }

    .profile-header img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #007bff;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .profile-header img:hover {
      transform: scale(1.1);
      box-shadow: 0 8px 15px rgba(0, 123, 255, 0.5);
    }

    .profile-header h2 {
      font-size: 1.8rem;
      font-weight: 600;
      color: #333;
      margin-top: 15px;
    }

    /* Profile details layout */
    .profile-details {
      font-size: 1.1rem;
      color: #333;
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 8px;
      box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
      margin-bottom: 20px;
    }

    .profile-details .row {
      margin-bottom: 15px;
    }

    .profile-details .row .label {
      font-weight: bold;
      color: #007bff;
    }

    .profile-details .row .info {
      color: #555;
    }

    /* Button styles */
    .action-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
    }

    .btn {
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
    }

    .btn-edit {
      background-color: #007bff;
      color: #fff;
      border: none;
      transition: background-color 0.3s;
    }

    .btn-edit:hover {
      background-color: #0056b3;
    }

    .btn-delete {
      background-color: #dc3545;
      color: #fff;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .btn-delete:hover {
      background-color: #c82333;
    }

    /* Modal styles */
    .modal-content {
      border-radius: 10px;
    }

    .modal-header {
      background-color: #007bff;
      color: #fff;
    }

    .modal-body {
      padding: 25px;
    }

    .form-label {
      font-weight: bold;
      color: #007bff;
    }

    .form-control {
      margin-bottom: 20px;
    }

    .form-check-label {
      font-size: 0.9rem;
    }

    .preview-img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      display: none;
      border-radius: 10px;
      margin-top: 10px;
    }

    .preview-img.show {
      display: block;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
      .profile-header h1 {
        font-size: 2rem;
      }

      .profile-header h2 {
        font-size: 1.5rem;
      }

      .profile-details {
        padding: 15px;
      }

      .action-buttons {
        flex-direction: column;
        align-items: center;
      }

      .btn {
        width: 100%;
        margin: 10px 0;
      }
    }
  </style>
</head>
<body>

  <x-header />


  <!-- Dropdown Menu -->
  <div class="dropdown" style="position: absolute; top: 140px; right: 120px;">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" 
      style="background-color: #007bff; border-color: #0056b3; font-size: 1rem; font-weight: bold; color: #fff; border-radius: 8px; padding: 12px 30px; box-shadow: 0 5px 10px rgba(0, 123, 255, 0.2); transition: all 0.3s ease-in-out;">
      Navigation
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width: 250px; padding: 10px 0; border-radius: 10px; border: 1px solid #ddd; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);">
      <li><a class="dropdown-item" href="#" style="font-size: 1rem; padding: 12px 25px; color: #333; font-weight: 500; transition: all 0.3s ease-in-out;">Appointments</a></li>
      <li><a class="dropdown-item" href="#" style="font-size: 1rem; padding: 12px 25px; color: #333; font-weight: 500; transition: all 0.3s ease-in-out;">Reports</a></li>
      <li><a class="dropdown-item" href="#" style="font-size: 1rem; padding: 12px 25px; color: #333; font-weight: 500; transition: all 0.3s ease-in-out;">XYZ</a></li>
    </ul>
  </div>

  <div class="container-scroller">
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="profile-container">
          <div class="profile-header">
            <h1>Profile</h1>
            <img src="{{ asset('uploads/profile/' . $user->picture) }}" alt="{{ $user->name ?? 'User Profile Picture' }}">
            <h2>{{ $user->name }}</h2>
          </div>

          <div class="profile-details">
            <div class="row">
              <div class="col">
                <div class="label">Name:</div>
                <div class="info">{{ $user->name }}</div>
              </div>
              <div class="col">
                <div class="label">Email:</div>
                <div class="info">{{ $user->email }}</div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="label">Location:</div>
                <div class="info">{{ $user->location ?? 'Not Set' }}</div>
              </div>
              <div class="col">
                <div class="label">Role:</div>
                <div class="info">{{ $user->type ?? 'Not Set' }}</div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="label">Joined At:</div>
                <div class="info">{{ $user->created_at->format('d-m-Y') }}</div>
              </div>
              <div class="col">
                <div class="label">Last Logged In:</div>
                <div class="info">{{ $user->updated_at->format('d-m-Y') }}</div>
              </div>
            </div>
          </div>

          <div class="action-buttons">
            <button class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
            <a href="{{ route('user.delete', ['id' => $user->id]) }}" class="btn btn-delete">Delete Profile</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Profile Modal -->
  <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('user.update', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" disabled>
            </div>
            <div class="mb-3">
              <label for="location" class="form-label">Location</label>
              <input type="text" class="form-control" id="location" name="location" value="{{ $user->location ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password">
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="showPassword">
                <label class="form-check-label" for="showPassword">Show Password</label>
              </div>
            </div>
            <div class="mb-3">
              <label for="profile_picture" class="form-label">Profile Picture</label>
              <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(event)">
              <img id="imagePreview" class="preview-img" src="#" alt="Image Preview">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <x-footer />

  <script>
    // Show/Hide password toggle
    document.getElementById('showPassword').addEventListener('change', function() {
      const passwordField = document.getElementById('password');
      if (this.checked) {
        passwordField.type = 'text';
      } else {
        passwordField.type = 'password';
      }
    });

    // Image preview function
    function previewImage(event) {
      const file = event.target.files[0];
      const reader = new FileReader();

      reader.onload = function(e) {
        const img = document.getElementById('imagePreview');
        img.src = e.target.result;
        img.classList.add('show');
      }

      if (file) {
        reader.readAsDataURL(file);
      }
    }
  </script>

  @include('admin.script')
</body>
</html>
