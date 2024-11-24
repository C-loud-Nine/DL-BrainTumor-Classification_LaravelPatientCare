<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.css')
    <style>
        /* Profile Styles */
        .profile-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: 2px solid #007bff; /* Border added to profile container */
        }

        .profile-header {
            text-align: center;
            margin-bottom: 40px; /* Increased space between header and profile details */
            position: relative;
        }

        .profile-header h1 {
            font-size: 2rem; /* More proportional header size */
            font-weight: 700;
            color: #343a40;
            margin-bottom: 10px; /* Space between title and profile picture */
        }

        .profile-header img {
            width: 150px; /* Square profile picture */
            height: 150px;
            border-radius: 8px; /* Slight rounding of corners */
            margin-bottom: 20px;
            object-fit: cover;
            border: 4px solid #007bff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .profile-header img:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 15px rgba(0, 123, 255, 0.5); /* Hover effect for image */
        }

        .profile-header h2 {
            font-size: 1.5rem; /* Smaller, more proportional subtitle */
            font-weight: 600;
            color: #007bff;
            margin-top: 10px;
        }

        .profile-details {
            font-size: 1rem; /* Slightly smaller text for readability */
            margin-bottom: 20px;
            color: #333333;
            line-height: 1.6;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            background-color: #f9f9f9;
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .profile-details:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Hover effect for details */
            border-color: #007bff;
        }

        .profile-details .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .profile-details .row .col {
            flex: 1;
            padding: 0 10px;
        }

        .profile-details .row .label {
            font-weight: bold;
            color: #007bff;
        }

        .profile-details .row .info {
            color: #555;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 25px;
            font-size: 1rem; /* Smaller button font size */
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: inline-block;
            width: 48%;
            position: relative;
        }

        .btn-edit {
            background-color: #007bff;
            color: white;
            text-align: center;
            border: 2px solid transparent;
        }

        .btn-edit:hover {
            background-color: #0056b3;
            transform: scale(1.05);
            border: 2px solid #0056b3;
            z-index: 1; /* Ensures the button is above the others when hovered */
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            text-align: center;
            border: 2px solid transparent;
        }

        .btn-delete:hover {
            background-color: #c82333;
            transform: scale(1.05);
            border: 2px solid #c82333;
            z-index: 1; /* Ensures the button is above the others when hovered */
        }

        /* Image preview styles */
        .preview-img {
            display: none;
            margin-top: 10px;
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin: 10px auto; /* Centers the image horizontally */
        }

        /* Modal styles */
        .modal-body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        @include('admin.sidebar')
        @include('admin.navbar')

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="profile-container">
                    <div class="profile-header">
                        <h1>Admin Profile</h1>
                        <img src="{{ asset('uploads/profile/' . $admin->picture) }}" alt="{{ $admin->name }}">
                        <h2>{{ $admin->name }}</h2>
                    </div>

                    <div class="profile-details">
                        <div class="row">
                            <div class="col">
                                <div class="label">Name:</div>
                                <div class="info">{{ $admin->name }}</div>
                            </div>
                            <div class="col">
                                <div class="label">Email:</div>
                                <div class="info">{{ $admin->email }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="label">Location:</div>
                                <div class="info">{{ $admin->location ?? 'Not Set' }}</div>
                            </div>
                            <div class="col">
                                <div class="label">Role:</div>
                                <div class="info">{{ $admin->type ?? 'Not Set' }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="label">Joined At:</div>
                                <div class="info">{{ $admin->created_at->format('d-m-Y') }}</div>
                            </div>
                            <div class="col">
                                <div class="label">Last Logged In:</div>
                                <div class="info">{{ $admin->updated_at->format('d-m-Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                        <a href="{{ route('admin.delete', $admin->id) }}" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this profile?')">Delete Profile</a>
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
                <form action="{{ route('admin.update', ['id' => $admin->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $admin->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $admin->email }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ $admin->location ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="showPassword">
                                <label class="form-check-label" for="showPassword">
                                    Show Password
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input class="form-control" type="file" id="profile_picture" name="profile_picture" onchange="previewImage(event)">
                            <img class="preview-img" id="preview" alt="Image Preview">
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

    @include('admin.script')
    <script>
        // Toggle password visibility
        document.getElementById('showPassword').addEventListener('change', function () {
            const passwordField = document.getElementById('password');
            passwordField.type = this.checked ? 'text' : 'password';
        });

        // Image preview function
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function (e) {
                const img = document.getElementById('preview');
                img.src = e.target.result;
                img.style.display = 'block'; // Show the preview image
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                // Reset preview if no file is selected
                const img = document.getElementById('preview');
                img.src = '';
                img.style.display = 'none';
            }
        }

        // Reset modal content on open
        document.getElementById('editProfileModal').addEventListener('shown.bs.modal', function () {
            const img = document.getElementById('preview');
            const profileInput = document.getElementById('profile_picture');

            // Reset file input and preview image
            profileInput.value = '';
            img.src = '';
            img.style.display = 'none';
        });
    </script>
</body>
</html>
