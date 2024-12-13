<x-header />

<div class="container mt-5">
    <h1 class="text-center mb-4">Your Appointments</h1>

    <!-- Success/Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    <!-- Confirmed Appointments -->


    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5>Confirmed Appointments</h5>
        </div>
        <div class="card-body">
            @if ($confirmed->isEmpty())
                <div class="alert alert-warning" role="alert">
                    No confirmed appointments found!
                </div>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($confirmed as $appointment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $appointment->doctor }}</td>
                                <td>{{ $appointment->date }}</td>
                                <td><span class="badge badge-success">Confirmed</span></td>
                                <td>{{ $appointment->message }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    <!-- Approved Appointments -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5>Approved Appointments</h5>
        </div>
        <div class="card-body">
            @if ($approved->isEmpty())
                <div class="alert alert-warning" role="alert">
                    No approved appointments found!
                </div>
            @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($approved as $appointment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $appointment->doctor }}</td>
                                <td>{{ $appointment->date }}</td>
                                <td><span class="badge badge-success">Approved</span></td>
                                <td>{{ $appointment->message }}</td>
                                <td>
                                    <!-- Confirm button -->
                                    <form action="{{ route('user.confirm', ['id' => $appointment->id]) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Confirm</button>
                                    </form>

                                    <form action="{{ route('user.reject', ['id' => $appointment->id]) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Pending/Rejected Appointments -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-white">
            <h5>Pending and Rejected Appointments</h5>
        </div>
        <div class="card-body">
            @if ($pendingRejected->isEmpty())
                <div class="alert alert-warning" role="alert">
                    No pending or rejected appointments found!
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingRejected as $appointment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $appointment->doctor }}</td>
                                <td>{{ $appointment->date }}</td>
                                <td>{{ $appointment->message }}</td>
                                <td>
                                    <span class="badge {{ $appointment->status === 'pending' ? 'badge-warning' : 'badge-danger' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <!-- Reject button for pending/rejected appointments -->
                                    <form action="{{ route('user.reject', $appointment->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
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
@include('admin.script')
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

    .badge-danger {
        background-color: #dc3545;
        color: #fff;
    }

    .badge-success {
        background-color: #28a745;
        color: #fff;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }
</style>
