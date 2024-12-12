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

/* Button container styles */
.action-buttons {
  display: flex;
  justify-content: space-between;
  margin-top: 30px;
  gap: 20px; /* Added gap for better spacing between buttons */
}

/* General button styles */
.btn {
  padding: 12px 25px; /* Increased padding for better button size */
  border-radius: 8px; /* Smoother rounded corners */
  font-size: 1.1rem; /* Slightly larger font size */
  font-weight: bold; /* Make the font bold by default */
  cursor: pointer;
  border: none;
  transition: all 0.3s ease; /* Smooth transition for hover, focus, and active states */
}

/* Edit button styles (Always blue) */
.btn-edit {
  background-color: #007bff; /* Primary blue */
  color: #fff;
  box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2); /* Soft shadow for depth */
}

.btn-edit:hover {
  background-color: #0056b3; /* Darker blue on hover */
  box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3); /* Increased shadow intensity */
  transform: translateY(-3px); /* Slight lift effect */
}

.btn-edit:active {
  background-color: #004085; /* Even darker blue when clicked */
  box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2); /* Softer shadow when clicked */
  transform: translateY(0); /* Return to normal position */
}

/* Delete button styles (Always red) */
.btn-delete {
  background-color: #dc3545; /* Red background for delete */
  color: #fff;
  box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2); /* Soft shadow for depth */
}

.btn-delete:hover {
  background-color: #c82333; /* Darker red on hover */
  box-shadow: 0 6px 12px rgba(220, 53, 69, 0.3); /* Increased shadow intensity */
  transform: translateY(-3px); /* Slight lift effect */
}

.btn-delete:active {
  background-color: #bd2130; /* Darker red when clicked */
  box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2); /* Softer shadow when clicked */
  transform: translateY(0);
  color: #fff; /* Ensure text is white */
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

    

     /* Sidebar container styling */
  .sidebar {
    position: fixed;
    top: 140px; /* Align with existing position */
    right: 100px;
    width: 220px;
    padding: 15px 10px;
    background-color: #f8f9fa; /* Light background */
    border: 5px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
    z-index: 1000; /* Ensure it stays on top */
    transition: all 0.3s ease;
    border-color: #63628B; /* Primary blue border color */
  }

  /* Sidebar menu items */
  .sidebar-menu {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  /* Individual sidebar links */
  .sidebar-link {
    display: block;
    font-size: 1rem;
    font-weight: 500;
    color: #333;
    padding: 12px 20px;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.3s ease;
  }

  /* Hover effects for links */
  .sidebar-link:hover {
    background-color: #63628B; /* Primary blue */
    color: #fff;
    box-shadow: 0 5px 10px rgba(0, 123, 255, 0.2);
  }

  /* Responsive behavior */
  @media (max-width: 768px) {
    .sidebar {
      width: 100%; /* Full width for smaller screens */
      right: 0;
      top: 100px;
      padding: 20px;
    }

    .sidebar-link {
      text-align: center; /* Center align for smaller screens */
    }
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
  
  
 
  <div class="sidebar" id="sidebar">
  <ul class="sidebar-menu">
    <li><a href="{{ route('doctorapplist') }}" class="sidebar-link">Appointments</a></li>
    <li><a href="#" class="sidebar-link">Reports</a></li>
    <li><a href="#" class="sidebar-link">XYZ</a></li>
  </ul>
</div>

  <div class="container-scroller">
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="profile-container">
          <div class="profile-header">
            <h1>Profile</h1>
            <img src="{{ asset('uploads/profile/' . $doc->picture) }}" alt="{{ $doc->name ?? 'User Profile Picture' }}">
            <h2>{{ $doc->name }}</h2>
          </div>

          <div class="profile-details">
            <div class="row">
              <div class="col">
                <div class="label">Name:</div>
                <div class="info">{{ $doc->name }}</div>
              </div>
              <div class="col">
                <div class="label">Email:</div>
                <div class="info">{{ $doc->email }}</div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="label">Phone :</div>
                <div class="info">{{ $doc->doctor->phone ?? 'Not Set' }}</div>
              </div>
              <div class="col">
                <div class="label">Specialization :</div>
                <div class="info">{{ $doc->doctor->specialization ?? 'Not Set' }}</div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="label">Location:</div>
                <div class="info">{{ $doc->location ?? 'Not Set' }}</div>
              </div>
              <div class="col">
                <div class="label">Role:</div>
                <div class="info">{{ $doc->type ?? 'Not Set' }}</div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="label">Joined At:</div>
                <div class="info">{{ $doc->created_at->format('d-m-Y') }}</div>
              </div>
              <div class="col">
                <div class="label">Last Logged In:</div>
                <div class="info">{{ $doc->updated_at->format('d-m-Y') }}</div>
              </div>
            </div>
          </div>

          <div class="action-buttons">
            <button class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
            <a href="{{ route('user.deletedoc', ['id' => $doc->id]) }}" class="btn btn-delete">Delete Profile</a>
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
        <form action="{{ route('user.updatedoc', ['id' => $doc->id]) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ $doc->name }}">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ $doc->email }}" disabled>
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Phone</label>
              <input type="text" class="form-control" id="phone" name="phone" value="{{ $doc->doctor ? $doc->doctor->phone : 'N/A' }}">
          </div>
          <div class="mb-3">
                <label for="specialization" class="form-label">Specialization</label>
                <select class="form-control" id="specialization" name="specialization">
                    @foreach($specializations as $specialization)
                        <option value="{{ $specialization->name }}" 
                            {{ $doc->doctor && $doc->doctor->specialization == $specialization->name ? 'selected' : '' }}>
                            {{ $specialization->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="mb-3">
              <label for="location" class="form-label">Location</label>
              <input type="text" class="form-control" id="location" name="location" value="{{ $doc->location ?? '' }}">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" >
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
  @include('admin.script')


  <script>
  // JavaScript to add hover effect to the dropdown items
  const dropdownItems = document.querySelectorAll('.dropdown-item');
  dropdownItems.forEach(item => {
    item.addEventListener('mouseenter', () => {
      item.style.backgroundColor = '#f8f9fa';
      item.style.color = '#007bff';
    });
    item.addEventListener('mouseleave', () => {
      item.style.backgroundColor = '';
      item.style.color = '#333';
    });
  });


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

  <script src="../assets/js/jquery-3.5.1.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/owl-carousel/js/owl.carousel.min.js"></script>
  <script src="../assets/vendor/wow/wow.min.js"></script>
  <script src="../assets/js/theme.js"></script>

</body>
</html>
