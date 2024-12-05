<x-header />


<div class="container mt-5">
    <h1 class="text-center mb-4">Your Appointments</h1>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Appointment Details</h5>
        </div>
        <div class="card-body">
            @if ($appointments->isEmpty())
                <div class="alert alert-warning" role="alert">
                    No appointments found!
                </div>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Message</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appointment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $appointment->doctor }}</td>
                                <td>{{ $appointment->date }}</td>
                                <td>{{ $appointment->message }}</td>
                                <td>
                                    <span class="badge {{ $appointment->status === 'pending' ? 'badge-warning' : 'badge-success' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>


<x-footer />

<style>
    .card-header {
        font-weight: bold;
        font-size: 1.2rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #fff;
    }

    .badge-success {
        background-color: #28a745;
        color: #fff;
    }
</style>
