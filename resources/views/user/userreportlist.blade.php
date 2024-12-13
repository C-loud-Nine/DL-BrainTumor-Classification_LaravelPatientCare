<x-header />

<div class="container11">
    <div class="text-center mb-5">
        <h1 class="text-primary fw-bold">MRI Report List</h1>
        <p class="text-muted">A list of all MRI reports uploaded by users.</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger custom-error-message text-center">
            <p class="error-text">{{ session('error') }}</p>
        </div>
    @endif

    <div class="table-responsive shadow-lg rounded-lg">
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th scope="col">User ID</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Doctor Name</th>
                    <th scope="col">Report</th>
                    <th scope="col">Prediction</th>
                    <th scope="col">Confidence</th>
                    <th scope="col">Time</th>
                    <th scope="col">Report Image</th>  <!-- New column for image -->
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                <tr>
                    <td>{{ $report->user_id }}</td>
                    <td>{{ $report->user_name }}</td>
                    <td>
                        {{ $report->scanner_name && $report->scanner_name != $report->user_name ? $report->scanner_name : 'Not Assigned' }}
                    </td>
                    <td>
                        <a href="{{ route('generateReport', $report->id) }}" class="btn btn-info btn-sm">View Report</a>
                    </td>

                    <td>{{ $report->report_class }}</td>
                    <td>{{ number_format($report->confidence * 100, 2) }}%</td>
                    <td class="time-column">{{ $report->created_at->format('d-m-Y H:i') }}</td>
                    
                    <!-- New column for displaying image -->
                    <td>
                        @if($report->report_image)  <!-- Check if there is an image path -->
                            <img src="{{ asset('uploads/mri/'.$report->report_image) }}" alt="Report Image" style="width: 100px; height: auto;"/>
                        @else
                            No Image
                        @endif
                    </td>

                    <td>
                        <form action="{{ route('deleteReport') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?')">
                            @csrf
                            <input type="hidden" name="report_id" value="{{ $report->id }}">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<x-footer />

@include('admin.script')

<style>
    /* Global Style Enhancements */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fc;
        color: #333;
    }

    .container-fluid {
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .text-primary {
        color: #007bff;
    }

    /* Custom Error Message */
    .custom-error-message {
        margin-bottom: 20px;
        font-weight: bold;
        padding: 20px;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        font-size: 16px;
    }

    .error-text {
        color: #721c24;
        margin: 0;
    }

    /* Table Styling */
    .table {
        margin-top: 20px;
        border-collapse: collapse;
        width: 100%;
    }

    .table th,
    .table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #007bff;
        color: #fff;
        font-weight: bold;
    }

    .table td {
        background-color: #f9f9f9;
        color: #333;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .table-responsive {
        overflow-x: auto;
        margin-top: 20px;
    }

    /* Button Styles */
    .btn-info, .btn-danger {
        border-radius: 5px;
        padding: 8px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: #fff;
    }

    .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    /* Row Styling for Interactive Cells */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f2f2f2;
    }

    /* Responsive Layout */
    @media (max-width: 768px) {
        .table th, .table td {
            padding: 10px;
        }
    }

    /* Time column style */
    .time-column {
        font-size: 14px;
        color: #555;
    }

    /* Container for content */
    .container11 {
        padding: 30px;
        max-width: 95%;
        margin: auto;
    }

    /* Table Header Enhancement */
    .table th {
        text-align: left;
        padding-left: 20px;
        background-color: #007bff;
        color: white;
    }
</style>
