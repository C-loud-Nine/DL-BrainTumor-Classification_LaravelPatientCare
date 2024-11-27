<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
    <style>

h1, h2 {
  font-weight: bold; /* Make headings bold */
  color: white; /* Set heading color to white */
}

.table thead {
  background-color: #34495e; /* Darker background for the table header */
}

.table th, .table td {
  color: white; /* Make table text white */
  font-weight: bold; /* Make table text bold */
  padding: 12px; /* Add padding to table cells */
}

/* Specific rule to ensure input field text is white */
.input-group input, .form-control {
  max-width: 400px; /* Limit the width of the input field */
  padding: 12px; /* Add padding to the input field */
  font-size: 16px; /* Increase the font size */
  color: white !important; /* Ensure the text color is white */
  background-color: #34495e; /* Dark background to match the theme */
  border: 1px solid #95a5a6; /* Light border for better contrast */
  font-weight: bold; /* Make input text bold */
}

.input-group button {
  padding: 10px 20px; /* Add padding to the button */
  font-size: 16px; /* Increase button text size */
}

.alert {
  font-weight: bold; /* Make success message text bold */
}

.text-danger {
  color: red; /* Set error message color to red */
}

/* Hover effect for table rows */
.table tbody tr:hover {
  background-color: #95a5a6; /* Light gray background on hover */
  cursor: pointer; /* Change cursor to indicate clickability */
}

/* Optional: Add hover effect for delete button */
.btn-danger:hover {
  background-color: #c0392b; /* Darker red for delete button hover */
  border-color: #e74c3c; /* Change border color on hover */
}

    </style>
  </head>
  <body>
    <div class="container-scroller">
      @include('admin.sidebar')
      @include('admin.navbar')

      <div class="main-panel">
        <div class="container mt-5">
          <h1>Manage Specializations</h1>
          
          <!-- Success message -->
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          
          <!-- Add New Specialization -->
          <form action="{{ route('admin.storeSpecialization') }}" method="POST" class="mb-3">
              @csrf
              <div class="input-group">
                  <input type="text" name="name" class="form-control" placeholder="Enter specialization name" required>
                  <button type="submit" class="btn btn-primary">Add</button>
              </div>
              @error('name')
                  <div class="text-danger mt-1">{{ $message }}</div>
              @enderror
          </form>

          <!-- Specializations List -->
          <h2>Existing Specializations</h2>
          <table class="table table-bordered">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Specialization</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse ($specializations as $specialization)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $specialization->name }}</td>
                          <td>
                              <form action="{{ route('admin.destroySpecialization', $specialization->id) }}" method="POST">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                              </form>
                          </td>
                      </tr>
                  @empty
                      <tr>
                          <td colspan="3" class="text-center">No specializations available.</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
        </div>
      </div>
    </div>

    @include('admin.script')
  </body>
</html>
