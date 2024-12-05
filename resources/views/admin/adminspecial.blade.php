<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
    
    <style>
      /* h1, h2 {
        font-weight: bold;
        color: white;
      }

      .table thead {
        background-color: #34495e;
      }

      .table th, .table td {
        color: white;
        font-weight: bold;
        padding: 12px;
      }

      .btn-action {
        width: 90px;
        padding: 5px;
        font-size: 14px;
        font-weight: bold;
      }

      .btn-action:hover {
        opacity: 0.8;
      } */

      
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


      .modal-content {
        background-color: #34495e;
        color: white;
        border: none;
      }

      .modal-header {
        border-bottom: 1px solid #95a5a6;
      }

      .modal-footer {
        border-top: 1px solid #95a5a6;
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
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse ($specializations as $specialization)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $specialization->name }}</td>
                          <td>
                            <div class="table-actions">
                              <!-- Update Button -->
                                <button 
                                  type="button" 
                                  class="btn btn-success btn-sm btn-action"
                                  onclick="openUpdateModal('{{ $specialization->id }}', '{{ $specialization->name }}')">
                                  Update
                                </button>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.destroySpecialization', $specialization->id) }}" method="POST">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-danger btn-sm btn-action">Delete</button>
                                </form>
                              </div>
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

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="updateModalLabel">Update Specialization</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="updateForm" method="POST">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label for="specializationName" class="form-label">Specialization Name</label>
                <input type="text" class="form-control" id="specializationName" name="name" required>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success" form="updateForm">Update</button>
          </div>
        </div>
      </div>
    </div>

    @include('admin.script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      function openUpdateModal(id, currentName) {
          // Set the current name in the modal input field
          document.getElementById('specializationName').value = currentName;
          // Set the action attribute for the form
          document.getElementById('updateForm').action = `/specializations/${id}`;
          // Show the modal
          const updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
          updateModal.show();
      }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  </body>
</html>
