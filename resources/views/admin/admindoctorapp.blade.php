<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
    <style>
      body {
        color: #2c3e50;
        font-family: 'Arial', sans-serif;
      }

      .content-wrapper {
        padding: 20px;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        margin: 20px auto;
        max-width: 1200px;
        position: relative;
      }

      h2 {
        font-size: 2rem;
        color: #2c3e50;
        margin-bottom: 20px;
        text-align: center;
        font-weight: bold;
      }

      .dropdown-container {
        position: absolute;
        top: 20px;
        right: 20px;
      }

      .dropdown-container select {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 300px;
        background-color: #f4f4f4;
        color: #2c3e50;
      }

      .appointment-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 16px;
        text-align: center;
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
      }

      .appointment-table th,
      .appointment-table td {
        padding: 15px;
        transition: all 0.2s ease;
      }

      .appointment-table th {
        background-color: #2c3e50;
        color: #fff;
        text-transform: uppercase;
        font-weight: bold;
      }

      .appointment-table td {
        border-bottom: 1px solid #ddd;
        color: #555;
      }

      .appointment-table tr.date-row {
        background-color: #f4f4f4;
        font-weight: bold;
        color: #2c3e50;
        text-align: left;
      }

      .appointment-table tr:nth-child(even) {
        background-color: #f9f9f9;
      }

      .appointment-table tr:hover {
        background-color: #eaf6ff;
        transform: scale(1.01);
        transition: all 0.2s ease-in-out;
      }

      .appointment-count {
        float: right;
        font-size: 14px;
        color: #555;
      }

      .alert {
        color: #e74c3c;
        font-size: 16px;
        text-align: center;
        font-weight: bold;
      }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      @include('admin.sidebar')
      @include('admin.navbar')

      <div class="main-panel">
        <div class="content-wrapper">
          <h2>Doctor Appointments</h2>

          <div class="dropdown-container">
            <select id="doctorDropdown" onchange="fetchAppointments()">
              <option value="" selected disabled>-- Select a Doctor --</option>
              @foreach ($doctors as $doctor)
                <option value="{{ $doctor }}">{{ $doctor }}</option>
              @endforeach
            </select>
          </div>

          <div id="appointmentTableContainer">
            <h3 style="text-align: center; color: #2c3e50; font-size: 1.5rem; margin-bottom: 15px;">Confirmed Appointments</h3>
            <table class="appointment-table" id="appointmentTable">
              <thead>
                <tr>
                  <th>Patient Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Message</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="4" class="alert">Select a doctor to view their confirmed appointments.</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      @include('admin.script')
      <script>
        document.addEventListener("DOMContentLoaded", () => {
          const dropdown = document.getElementById("doctorDropdown");
          dropdown.selectedIndex = 0; // Reset dropdown to default on page reload
        });

        function fetchAppointments() {
          const doctor = document.getElementById("doctorDropdown").value;

          if (!doctor) {
            alert("Please select a doctor");
            return;
          }

          fetch('{{ route('fetch.doctor.appointments') }}', {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({ doctor }),
          })
            .then((response) => response.json())
            .then((data) => {
              const tableBody = document.querySelector("#appointmentTable tbody");
              tableBody.innerHTML = "";

              if (Object.keys(data).length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" class="alert">No confirmed appointments found for this doctor.</td></tr>';
              } else {
                Object.keys(data).forEach((date) => {
                  const appointments = data[date];

                  // Add date row with appointment count
                  tableBody.innerHTML += `
                    <tr class="date-row">
                      <td colspan="4">${date} <span class="appointment-count">(${appointments.length} appointments)</span></td>
                    </tr>
                  `;

                  // Add rows for each appointment on that date
                  appointments.forEach((appointment) => {
                    const row = `
                      <tr>
                        <td>${appointment.name}</td>
                        <td>${appointment.email}</td>
                        <td>${appointment.phone}</td>
                        <td>${appointment.message}</td>
                      </tr>
                    `;
                    tableBody.innerHTML += row;
                  });
                });
              }
            })
            .catch((error) => console.error("Error fetching appointments:", error));
        }
      </script>
  </body>
</html>
