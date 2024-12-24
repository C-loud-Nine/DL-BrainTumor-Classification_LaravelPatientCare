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
                <th>ID</th>
                <th>Scanner Name</th>
                <th>User Name</th>
                <th>Report Class</th>
                <th>Confidence</th>
                <th>Report Image</th>
                <th>Created At</th>
                <th>Verdict</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr data-report-id="{{ $report->id }}"  data-scanner-name="{{ $report->scanner_name }}">
                    <td>{{ $report->id }}</td>
                    <td>{{ $report->scanner_name }}</td>
                    <td>{{ $report->user_name }}</td>
                    <td>{{ $report->report_class }}</td>
                    <td>{{ $report->confidence }}%</td>
                    <td>
                        <a href="#" class="view-image" data-image="{{ asset('uploads/mri/'.$report->report_image) }}">
                            <img src="{{ asset('uploads/mri/'.$report->report_image) }}" alt="Report Image" width="100" height="100">
                        </a>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($report->created_at)->format('l, F j, Y h:i A') }}</td>
                    <td class="verdict-cell">
                        @php
                            // Get the verdict for this report
                            $verdict = \App\Models\RepVerdict::where('report_id', $report->id)->first();
                        @endphp
                        {{ $verdict->verdict ?? 'Pending' }}
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal for viewing and saving verdict -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="" id="modalImage" class="img-fluid" alt="Report Image">
                <form method="POST" id="verdictForm" action="{{ route('saveVerdict') }}">
                    @csrf
                    <input type="hidden" name="report_id" id="reportIdInput">
                    <div class="mt-3 text-center">
                        <button type="button" name="verdict" value="Yes" class="btn btn-success" id="yesButton">Yes</button>
                        <button type="button" name="verdict" value="No" class="btn btn-danger" id="noButton">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<x-footer />
@include('admin.script')

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Modal initialization
        const modalElement = document.getElementById('imageModal');
        const modal = new bootstrap.Modal(modalElement);

        // Open modal with image and report ID
        document.querySelectorAll('.view-image').forEach(function (element) {
            element.addEventListener('click', function (event) {
                event.preventDefault();

                const imageSrc = this.getAttribute('data-image');
                const reportId = this.closest('tr').getAttribute('data-report-id');

                document.getElementById('modalImage').src = imageSrc;
                document.getElementById('reportIdInput').value = reportId;

                modal.show();
            });
        });

        // Handle form submission with fetch
        document.getElementById('yesButton').addEventListener('click', function () {
            const reportId = document.getElementById('reportIdInput').value;
            const form = document.getElementById('verdictForm');
            const verdictInput = document.createElement('input');
            verdictInput.type = 'hidden';
            verdictInput.name = 'verdict';
            verdictInput.value = 'Yes';
            form.appendChild(verdictInput);
            form.submit();
        });

        document.getElementById('noButton').addEventListener('click', function () {
            const reportId = document.getElementById('reportIdInput').value;
            const form = document.getElementById('verdictForm');
            const verdictInput = document.createElement('input');
            verdictInput.type = 'hidden';
            verdictInput.name = 'verdict';
            verdictInput.value = 'No';
            form.appendChild(verdictInput);
            form.submit();
        });
    });

    
     // Script for filtering reports based on doctor selection
     document.getElementById('doctorFilter').addEventListener('change', function() {
        var selectedDoctor = this.value;
        var rows = document.querySelectorAll('#reportsTable tbody tr');

        rows.forEach(function(row) {
            var scannerName = row.getAttribute('data-scanner-name'); // Using the correct attribute

            if (selectedDoctor === '' || selectedDoctor === scannerName) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    
</script>

<style>
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

    /* Uniform modal size */
    .modal-content {
        border-radius: 10px;
        width: 100%;
        max-width: 600px; /* Set a max width to make the modal more consistent */
        margin: auto;
    }

    .modal-header {
        background-color: #007bff;
        color: white;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .modal-body {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .modal-body img {
        width: auto;
        height: 300px; /* Fixed height for image to ensure uniform modal size */
        object-fit: contain;
        margin-bottom: 20px;
    }

    .btn-close {
        background-color: transparent;
        border: none;
        font-size: 1.5rem;
    }

    /* Button styling */
    .btn-success, .btn-danger {
        font-size: 1rem;
        padding: 10px 20px;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .btn-success:hover, .btn-danger:hover {
        opacity: 0.8;
    }

    .btn-success {
        background-color: #28a745;
    }

    .btn-danger {
        background-color: #dc3545;
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

        /* Ensure modal is more responsive */
        .modal-content {
            max-width: 90%; /* Responsive modal size */
        }

        .modal-body img {
            height: 250px; /* Adjust image size for smaller screens */
        }
    }
</style>

