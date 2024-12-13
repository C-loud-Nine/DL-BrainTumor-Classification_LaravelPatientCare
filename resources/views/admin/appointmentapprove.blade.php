<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
    <style>
      /* Custom CSS for better readability */
      .card {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        padding: 20px;
      }

      .card-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
        color: #4b5563;
      }

      .table-hover tbody tr:hover {
        background-color: #f1f5f9;
      }

      .table th, .table td {
        color: #4b5563;
        text-align: center;
        vertical-align: middle;
      }

      .table th {
        font-weight: bold;
      }

      .form-control {
        border-radius: 5px;
        border: 1px solid #d1d5db;
        font-size: 0.9rem;
        padding: 5px;
        color: #4b5563;
      }

      .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 5px rgba(59, 130, 246, 0.5);
      }

      .btn-sm {
        padding: 5px 10px;
        font-size: 0.8rem;
      }

      .alert {
        margin-top: 10px;
        padding: 10px;
        font-size: 1rem;
        border-radius: 5px;
      }

      .btn-danger {
        background-color: #e3342f;
        border-color: #e3342f;
      }

      .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
      }

      .btn-success {
        background-color: #38a169;
        border-color: #38a169;
      }

      .btn-success:hover {
        background-color: #2f855a;
        border-color: #276749;
      }

      /* Style for select dropdown */
      select.form-control {
        background-color: #ffffff;
        color: #374151;
        font-size: 1rem;
        padding: 5px;
        border-radius: 5px;
      }

      select.form-control option {
        background-color: #ffffff;
        color: #4b5563;
        font-weight: bold;
        padding: 5px;
      }

      select.form-control option:hover {
        background-color: #3b82f6;
        color: #ffffff;
      }

      /* Responsive Table */
      @media (max-width: 768px) {
        .table th, .table td {
          font-size: 0.9rem;
        }

        .btn-sm {
          font-size: 0.7rem;
        }

        .card-title {
          font-size: 1.2rem;
        }
      }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      @include('admin.sidebar')
      @include('admin.navbar')

      <div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Manage Appointments</h4>

              <!-- Display success message -->
              @if(session('success'))
                <div class="alert alert-success">
                  {{ session('success') }}
                </div>
              @endif

              <!-- Display error message -->
              @if(session('error'))
                <div class="alert alert-danger">
                  {{ session('error') }}
                </div>
              @endif

              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($appointments as $appointment)
                  <tr>
                    <td>{{ $appointment->id }}</td>
                    <td>{{ $appointment->name }}</td>
                    <td>{{ $appointment->email }}</td>
                    <td>{{ $appointment->phone }}</td>
                    <td>{{ $appointment->message }}</td>
                    <td>{{ $appointment->doctor }}</td>
                    <td>
                      <form action="{{ route('admin.appointmentapprove.update', $appointment->id) }}" method="POST">
                        @csrf
                        <input type="date" name="date" value="{{ $appointment->date }}" class="form-control">
                    </td>
                    <td>
                      <select name="status" class="form-control">
                        <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $appointment->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $appointment->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                      </select>
                    </td>
                    <td>
                      <button type="submit" class="btn btn-success btn-sm">Update</button>
                      </form>
                      <form action="{{ route('admin.appointmentconfirm.delete', $appointment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this appointment?')">Remove</button>
                        </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      @include('admin.script')
    </div>
  </body>
</html>
