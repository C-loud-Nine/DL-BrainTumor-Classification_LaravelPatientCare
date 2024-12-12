<x-header />

<div class="container my-5">
    <h1 class="text-center text-primary">Appointments for Dr. {{ $doctorName }}</h1>

    @foreach ($appointments as $date => $appointmentsOnDate)
        <div class="card my-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h5>
                <span class="badge badge-primary">{{ count($appointmentsOnDate) }} Appointments</span>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointmentsOnDate as $appointment)
                            <tr>
                                <td>{{ $appointment->name }}</td>
                                <td>{{ $appointment->email }}</td>
                                <td>{{ $appointment->phone }}</td>
                                <td>{{ $appointment->message }}</td>
                                <td>
                                    <span class="badge badge-{{ $appointment->status == 'pending' ? 'warning' : 'success' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>

<x-footer />

@include('admin.script')

<style>
    /* Styling for the container and the appointment table */
  

    .card {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .card-header {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        border-radius: 8px 8px 0 0;
    }

    .badge {
        background-color: #28a745;
        font-size: 0.9em;
        padding: 8px 12px;
        border-radius: 12px;
    }

    .badge-warning {
        background-color: #ffc107;
    }

    .badge-success {
        background-color: #28a745;
    }

    table {
        width: 100%;
        margin-top: 20px;
    }

    th, td {
        text-align: center;
        vertical-align: middle;
        padding: 10px;
    }

    th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    tbody tr:hover {
        background-color: #f1f1f1;
    }

    @media (max-width: 768px) {
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }

        table {
            font-size: 0.9em;
        }
    }
</style>