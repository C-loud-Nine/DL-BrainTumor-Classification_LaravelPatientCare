<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.css')
    <style>
        /* Styles for Doctors Table */
        .doctor-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1rem;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Header styling */
        .doctor-table thead {
            background-color: #007bff;
            color: #ffffff;
        }

        .doctor-table th {
            text-align: center;
            padding: 15px;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Table content styling */
        .doctor-table td {
            text-align: center;
            padding: 12px 15px;
            border: 1px solid #dddddd;
            vertical-align: middle;
            color: #4d4d4d;
            font-weight: bold;
        }

        .doctor-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .doctor-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .doctor-table tbody tr:hover {
            background-color: #e9ecef;
        }

        /* Image styling */
        .doctor-table td img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
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

        .btn-demote {
            background-color: #007bff; /* Blue color */
            color: white;
        }

        .btn-demote:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
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
                        <h1>Manage Doctors</h1>
                    </div>
                    <table class="doctor-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Specialization</th>
                                <th>Location</th>
                                <th>Picture</th>
                                <th>Joined At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($docs as $doc)
                                <tr>
                                    <td>{{ $doc->id }}</td>
                                    <td>{{ $doc->name }}</td>
                                    <td>{{ $doc->email }}</td>
                                    <td>{{ $doc->specialization ?? 'N/A' }}</td>
                                    <td>{{ $doc->location ?? 'N/A' }}</td>
                                    <td>
                                        <img src="{{ asset('uploads/profile/' . $doc->picture) }}" alt="{{ $doc->name }}" />
                                    </td>
                                    <td>{{ $doc->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <!-- Demote Button -->
                                        <a href="{{ route('admin.demote', ['id' => $doc->id]) }}" 
                                           class="btn btn-sm btn-demote" 
                                           onclick="return confirm('Are you sure you want to demote this doctor to a user?')">
                                            Demote
                                        </a>

                                        <!-- Delete Button -->
                                        <a href="{{ route('admin.deleteUser', ['id' => $doc->id]) }}" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center;">No doctors found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.script')
</body>
</html>
