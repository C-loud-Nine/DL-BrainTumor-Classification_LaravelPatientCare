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
    /* ===== Base Styles ===== */
    :root {
      --primary: #007bff;
      --primary-dark: #0056b3;
      --secondary: #63628B;
      --danger: #dc3545;
      --danger-dark: #c82333;
      --light: #f8f9fa;
      --white: #ffffff;
      --text: #333333;
      --text-light: #555555;
      --border: #dddddd;
      --shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      --radius: 10px;
      --transition: all 0.3s ease;
    }

    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f7fa;
      color: var(--text);
      line-height: 1.6;
      margin: 0;
      padding: 0;
    }

    /* ===== Layout Structure ===== */
    .container-scroller {
      width: 100%;
      overflow-x: hidden;
    }

    .main-panel {
      width: 100%;
      padding: 20px 15px;
    }

    /* ===== Profile Container ===== */
    .profile-container {
      max-width: 900px;
      margin: 30px auto;
      padding: 25px;
      background-color: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
    }

    /* ===== Profile Header ===== */
    .profile-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .profile-header h1 {
      font-size: 2.5rem;
      font-weight: bold;
      color: var(--primary);
      margin-bottom: 20px;
    }

    .profile-header img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid var(--primary);
      transition: var(--transition);
    }

    .profile-header img:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 15px rgba(0, 123, 255, 0.3);
    }

    .profile-header h2 {
      font-size: 1.8rem;
      font-weight: 600;
      color: var(--text);
      margin-top: 15px;
    }

    /* ===== Profile Details ===== */
    .profile-details {
      font-size: 1.1rem;
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 8px;
      box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
      margin-bottom: 20px;
    }

    .profile-details .row {
      display: flex;
      flex-wrap: wrap;
      margin-bottom: 15px;
    }

    .profile-details .col {
      flex: 1;
      min-width: 250px;
      margin-bottom: 10px;
    }

    .profile-details .label {
      font-weight: bold;
      color: var(--primary);
    }

    .profile-details .info {
      color: var(--text-light);
      padding-left: 10px;
    }

    /* ===== Action Buttons ===== */
    .action-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
      gap: 15px;
    }

    .btn {
      padding: 12px 20px;
      border-radius: 5px;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: var(--transition);
      text-align: center;
      border: none;
      flex: 1;
    }

    .btn-edit {
      background-color: var(--primary);
      color: var(--white);
    }

    .btn-edit:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
    }

    .btn-delete {
      background-color: var(--danger);
      color: var(--white);
      text-decoration: none;
    }

    .btn-delete:hover {
      background-color: var(--danger-dark);
      transform: translateY(-2px);
    }

    /* ===== Sidebar ===== */
    .sidebar {
      position: fixed;
      top: 140px;
      right: 20px;
      width: 220px;
      padding: 15px;
      background-color: var(--light);
      border: 3px solid var(--secondary);
      border-radius: var(--radius);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      z-index: 1000;
      transition: var(--transition);
    }

    .sidebar-menu {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .sidebar-link {
      display: block;
      padding: 12px 15px;
      margin: 5px 0;
      color: var(--text);
      text-decoration: none;
      border-radius: 5px;
      transition: var(--transition);
      font-weight: 500;
    }

    .sidebar-link:hover {
      background-color: var(--secondary);
      color: var(--white);
      transform: translateX(5px);
    }

    /* ===== Modal Styles ===== */
    .modal-content {
      border-radius: var(--radius);
      border: none;
    }

    .modal-header {
      background-color: var(--primary);
      color: var(--white);
      border-radius: var(--radius) var(--radius) 0 0;
      padding: 15px 20px;
    }

    .modal-title {
      font-weight: 600;
    }

    .modal-body {
      padding: 20px;
    }

    .form-label {
      font-weight: bold;
      color: var(--primary);
      margin-bottom: 8px;
    }

    .form-control {
      margin-bottom: 15px;
      border-radius: 5px;
      padding: 10px 15px;
      border: 1px solid var(--border);
      transition: var(--transition);
    }

    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .preview-img {
      width: 100%;
      height: 150px;
      object-fit: contain;
      display: none;
      border-radius: 5px;
      margin-top: 10px;
      border: 1px solid var(--border);
      background: #f9f9f9;
    }

    .preview-img.show {
      display: block;
    }

    /* ===== Responsive Adjustments ===== */
    @media (max-width: 992px) {
      .sidebar {
        right: 15px;
        width: 200px;
      }
    }

    @media (max-width: 768px) {
      .main-panel {
        padding: 15px;
      }
      
      .profile-container {
        margin: 20px auto;
        padding: 20px;
        width: calc(100% - 30px);
      }
      
      .sidebar {
        position: relative;
        top: auto;
        right: auto;
        width: calc(100% - 30px);
        margin: 0 auto 20px;
        padding: 10px;
      }
      
      .sidebar-menu {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 5px;
      }
      
      .sidebar-link {
        padding: 10px 15px;
        margin: 0;
        font-size: 0.9rem;
      }
      
      .profile-header h1 {
        font-size: 2rem;
      }
      
      .profile-header h2 {
        font-size: 1.5rem;
      }
      
      .profile-header img {
        width: 120px;
        height: 120px;
      }
      
      .action-buttons {
        flex-direction: column;
        gap: 10px;
      }
      
      .profile-details .col {
        min-width: 100%;
      }
    }

    @media (max-width: 480px) {
      .profile-container {
        padding: 15px;
      }
      
      .profile-header h1 {
        font-size: 1.8rem;
      }
      
      .profile-header h2 {
        font-size: 1.3rem;
      }
      
      .profile-header img {
        width: 100px;
        height: 100px;
      }
      
      .sidebar-link {
        padding: 8px 12px;
      }
      
      .btn {
        padding: 10px 15px;
        font-size: 0.9rem;
      }
      
      .modal-body {
        padding: 15px;
      }
    }
</style>
</head>
<body>

  <x-header />


  
  <div class="sidebar" id="sidebar">
  <ul class="sidebar-menu">
    <li><a href="{{ route('userapp') }}" class="sidebar-link">Appointments</a></li>
    <li><a href="{{ route('userreportlist') }}" class="sidebar-link">Reports</a></li>
    <li><a href="{{ route('appointmentpage') }}" class="sidebar-link">Make Appointment</a></li>
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