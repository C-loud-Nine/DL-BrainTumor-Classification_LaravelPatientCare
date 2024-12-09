<x-header />


<div class="page-section">
    <div class="container">
        <h1 class="text-center wow fadeInUp">Make an Appointment</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form class="main-form my-5 wow fadeIn" action="{{ route('appointment') }}" method="POST" id="appointmentForm">
            @csrf <!-- CSRF Token for security -->
            <div class="row mt-5">
                <div class="col-12 col-sm-6 py-2 wow fadeInLeft">
                    <input type="text" name="name" class="form-control" placeholder="Full name" required>
                </div>
                <div class="col-12 col-sm-6 py-2 wow fadeInRight">
                    <input type="email" name="email" class="form-control" placeholder="Email address.." required>
                </div>
                <div class="col-12 col-sm-6 py-2 wow fadeInLeft position-relative" data-wow-delay="300ms">
                    <input type="date" name="date" class="form-control" id="dateInput" required>
                    <div id="dateMessage" class="position-absolute text-muted mt-1" style="display: none;">
                        Please note: The selected date is not guaranteed. We will confirm a date within 7 days (+/-) of your selection.
                    </div>
                </div>
                <div class="col-12 col-sm-6 py-2 wow fadeInRight" data-wow-delay="300ms">
                    <div class="position-relative">
                        <input type="text" id="doctorSearch" name="doctor" class="form-control" placeholder="Search doctor by name or specialization..." autocomplete="off" required>
                        <ul id="doctorDropdown" class="dropdown-menu w-100" style="max-height: 200px; overflow-y: auto; display: none;"></ul>
                    </div>
                </div>
                <div class="col-12 py-2 wow fadeInUp" data-wow-delay="300ms">
                    <input type="text" name="phone" class="form-control" placeholder="Phone number.." required>
                </div>
                <div class="col-12 py-2 wow fadeInUp" data-wow-delay="300ms">
                    <textarea name="message" id="message" class="form-control" rows="6" placeholder="Enter message.." required></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3 wow zoomIn">Submit Request</button>
        </form>
    </div>
</div>

<x-footer />

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const doctorSearchInput = document.getElementById('doctorSearch');
        const doctorDropdown = document.getElementById('doctorDropdown');
        const dateInput = document.getElementById('dateInput');
        const dateMessage = document.getElementById('dateMessage');
        const form = document.getElementById('appointmentForm');

        let doctorsList = []; // To store all fetched doctors

        // Fetch all doctors when the page loads
        fetch('/get-all-doctors') // Update this endpoint to fetch all doctors
            .then(response => response.json())
            .then(data => {
                doctorsList = data; // Store all doctors in memory
            })
            .catch(error => console.error('Error fetching doctors:', error));

        // Show all doctors when the input field is clicked
        doctorSearchInput.addEventListener('focus', function () {
            if (doctorsList.length > 0) {
                renderDropdown(doctorsList); // Display all doctors
            }
        });

        // Filter doctors based on user input
        doctorSearchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            if (searchTerm.length < 2) {
                renderDropdown(doctorsList); // Show all doctors if input is less than 2 characters
            } else {
                const filteredDoctors = doctorsList.filter(doctor =>
                    doctor.name.toLowerCase().includes(searchTerm) ||
                    doctor.specialization.toLowerCase().includes(searchTerm)
                );
                renderDropdown(filteredDoctors); // Show filtered results
            }
        });

        // Function to render dropdown items
        function renderDropdown(doctors) {
            doctorDropdown.innerHTML = ''; // Clear existing dropdown items

            if (doctors.length > 0) {
                doctors.forEach(doctor => {
                    const option = document.createElement('li');
                    option.textContent = `${doctor.name} -- Specialist -- ${doctor.specialization}`;
                    option.className = 'dropdown-item';
                    option.style.cursor = 'pointer';
                    option.addEventListener('click', () => {
                        doctorSearchInput.value = doctor.name;
                        doctorDropdown.style.display = 'none'; // Hide dropdown after selection
                    });
                    doctorDropdown.appendChild(option);
                });
                doctorDropdown.style.display = 'block'; // Show dropdown
            } else {
                doctorDropdown.innerHTML = '<li class="dropdown-item text-muted">No doctors found</li>';
                doctorDropdown.style.display = 'block';
            }
        }

        // Close doctor dropdown when clicking outside
        document.addEventListener('click', function (event) {
            if (!doctorDropdown.contains(event.target) && event.target !== doctorSearchInput) {
                doctorDropdown.style.display = 'none';
            }
        });

        // Show date selection message
        dateInput.addEventListener('focus', function () {
            dateMessage.style.display = 'block';
        });

        // Hide the message if the user interacts outside of the date input
        document.addEventListener('click', function (event) {
            if (!dateInput.contains(event.target)) {
                dateMessage.style.display = 'none';
            }
        });

        // Reset the form on page load (after refresh)
        if ("{{ session('success') }}") {
            form.reset(); // Reset the form after success message
        }
    });
</script>

@include('admin.script')




<style>
#dateMessage {
    font-size: 0.9em;
    color: #6c757d;
    font-style: italic;
    background: #f8f9fa;
    padding: 0.5rem;
    border-radius: 4px;
    z-index: 10;
    max-width: 300px; /* Ensures text wrapping */
}

form .position-relative {
    position: relative;
}

.alert-success {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }
    .alert-dismissible .close {
        color: #155724;
    }

@media (max-width: 576px) {
    #dateMessage {
        max-width: 100%; /* Adjust for small screens */
    }
}
</style>
