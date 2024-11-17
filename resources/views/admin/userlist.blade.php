<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.css')
    <style>
        /* General table styling */
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1rem;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Header styling */
        .user-table thead {
            background-color: #343a40;
            color: #ffffff;
        }

        .user-table th {
            text-align: center;
            padding: 15px;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Table content styling - Bold text */
        .user-table td {
            text-align: center;
            padding: 12px 15px;
            border: 1px solid #dddddd;
            vertical-align: middle;
            color: #4d4d4d;
            font-weight: bold; /* Bold table content */
        }

        .user-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .user-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .user-table tbody tr:hover {
            background-color: #e9ecef;
        }

        /* Image styling */
        .user-table td img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        /* Table container */
        .table-container {
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        /* Title above the table */
        .table-header {
            margin-bottom: 20px;
            text-align: center;
        }

        .table-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #343a40;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #343a40;
            padding-bottom: 10px;
        }

        /* Button styling */
        .btn-sm {
            padding: 8px 16px;
            font-size: 0.95rem;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            width: 350px;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .modal h3 {
            font-size: 1.5rem;
            color: #343a40;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .modal a {
            display: block;
            padding: 12px;
            margin: 10px 0;
            text-align: center;
            font-size: 1rem;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
        }

        .modal .btn-admin {
            background-color: #BB847F;
            color: white;
        }

        .modal .btn-doctor {
            background-color: #3B7873;
            color: white;
        }

        .modal .btn-admin:hover, .modal .btn-doctor:hover {
            opacity: 0.8;
        }

        .modal button {
            padding: 10px;
            border-radius: 8px;
            background-color: #dc3545;
            color: white;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        @include('admin.sidebar')
        @include('admin.navbar')

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="table-container">
                    <div class="table-header">
                        <h1>Manage Users</h1>
                    </div>
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Location</th>
                                <th>Picture</th>
                                <th>Joined At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->type === 'Admin' ? 'Admin' : ($user->type === 'Doctor' ? 'Doctor' : 'User') }}</td>
                                    <td>{{ $user->location ?? 'N/A' }}</td>
                                    <td>
                                        <img src="uploads/profile/{{ $user->picture }}" alt="{{ $user->name }}" />
                                    </td>
                                    <td>{{ $user->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <!-- Promote Button -->
                                        <button class="btn btn-sm btn-info promote-btn" data-user-id="{{ $user->id }}">Promote</button>

                                        <!-- Delete Button without Form and CSRF -->
                                        <a href="{{ route('admin.deleteUser', ['id' => $user->id]) }}" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center;">No users found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for promotion -->
    <div class="modal" id="promoteModal">
        <div class="modal-content">
            <h3>Select a Role</h3>
            <a href="#" id="btnAdmin" class="btn-admin">Promote to Admin</a>
            <a href="#" id="btnDoctor" class="btn-doctor">Promote to Doctor</a>
            <button onclick="closeModal()">Close</button>
        </div>
    </div>

    @include('admin.script')

    <script>
        const promoteBtns = document.querySelectorAll('.promote-btn');
        const promoteModal = document.getElementById('promoteModal');
        const btnAdmin = document.getElementById('btnAdmin');
        const btnDoctor = document.getElementById('btnDoctor');
        let currentUserId;

        promoteBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                currentUserId = this.getAttribute('data-user-id');
                btnAdmin.setAttribute('href', '/admin/promote/' + currentUserId + '/admin');
                btnDoctor.setAttribute('href', '/admin/promote/' + currentUserId + '/doctor');
                promoteModal.style.display = 'flex';
            });
        });

        window.onclick = function(event) {
            if (event.target == promoteModal) {
                promoteModal.style.display = 'none';
            }
        };

        function closeModal() {
            promoteModal.style.display = 'none';
        }
    </script>
</body>
</html>
