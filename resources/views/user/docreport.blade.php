<x-header />

<div class="container my-5">
    <h1 class="text-center text-primary">Doctor's Reports</h1>

    <!-- Filter Dropdown -->
    <div class="mb-4 d-flex justify-content-end">
     
        <select class="form-select custom-dropdown" id="doctorFilter">
            <option value="">All Reports</option>
            <option value="{{ $doctorName }}">Your Reports ({{ $doctorName }})</option>
        </select>
    </div>

    <!-- Reports Table -->
    <table class="table table-bordered table-striped" id="reportsTable">
        <thead>
            <tr>
                <th>Scanner Name</th>
                <th>User Name</th>
                <th>Report Class</th>
                <th>Confidence</th>
                <th>Report Image</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr data-scanner-name="{{ $report->scanner_name }}">
                    <td>{{ $report->scanner_name }}</td>
                    <td>{{ $report->user_name }}</td>
                    <td>{{ $report->report_class }}</td>
                    <td>{{ $report->confidence }}%</td>
                    <td>
                        <a href="javascript:void(0);" class="view-image" data-image="{{ asset('uploads/mri/'.$report->report_image) }}">
                            <img src="{{ asset('uploads/mri/'.$report->report_image) }}" alt="Report Image" width="100" height="100">
                        </a>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($report->created_at)->format('l, F j, Y h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal for enlarging the image -->
<div class="modal" tabindex="-1" id="imageModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="" id="modalImage" class="img-fluid" alt="Report Image">
            </div>
        </div>
    </div>
</div>

<x-footer />

@include('admin.script')

<style>
    /* General styling for the page */
    

    .form-select {
        width: 250px;
        margin-bottom: 20px;
        border-radius: 8px;
        padding: 10px;
        border: 1px solid #ddd;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    /* Custom dropdown styling */
    .custom-dropdown {
        font-size: 1rem;
        background-color: #f8f9fa;
        color: #495057;
        border: 1px solid #ced4da;
    }

    .custom-dropdown:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .custom-dropdown option {
        background-color: #ffffff;
    }

    .table {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table td img {
        cursor: pointer;
    }

    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }

    .table thead {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .modal-content {
        border-radius: 10px;
    }

    .btn-close {
        background-color: transparent;
        border: none;
        font-size: 1.5rem;
    }

    /* Styling for the creation timestamp */
    .table td:last-child {
        font-size: 0.9rem;
        color: #6c757d;
    }

    /* Hover effect for the report image */
    .table td img:hover {
        opacity: 0.7;
    }

    /* Styling for the filter dropdown */
    .form-label {
        font-weight: bold;
        color: #333;
    }

    /* Positioning the filter dropdown to the right */
    .d-flex {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .mb-4 {
        margin-bottom: 30px;
    }

    /* Responsive design for smaller screens */
    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }
        
        .form-select {
            width: 100%;
        }
        
        .table td img {
            width: 80px;
            height: 80px;
        }
    }
</style>

<script>
    // Script to open the modal with enlarged image
    document.querySelectorAll('.view-image').forEach(function(element) {
        element.addEventListener('click', function() {
            var imageSrc = this.getAttribute('data-image');
            document.getElementById('modalImage').src = imageSrc;
            var myModal = new bootstrap.Modal(document.getElementById('imageModal'), {
                keyboard: true
            });
            myModal.show();
        });
    });

    // Script for filtering reports based on doctor selection
    document.getElementById('doctorFilter').addEventListener('change', function() {
        var selectedDoctor = this.value;
        var rows = document.querySelectorAll('#reportsTable tbody tr');

        rows.forEach(function(row) {
            var scannerName = row.getAttribute('data-scanner-name');

            if (selectedDoctor === '' || selectedDoctor === scannerName) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
