<x-header />

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctors</title>
  <style>
    /* Overall page styling */
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
      color: #333;
    }

    /* Header styling */
    .page-header {
      background: #ffffff;
      color: #333;
      padding: 30px 0;
      text-align: center;
      border-bottom: 1px solid #e0e0e0;
    }

    .page-header h1 {
      margin: 0;
      font-size: 2.5rem;
      font-weight: bold;
    }

    .page-header p {
      margin: 10px 0 0;
      font-size: 1.2rem;
      color: #666;
    }

    /* Container for Doctor Cards */
    .doctor-container {
      padding: 30px;
      max-width: 1000px;
      margin: auto;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 40px;
    }

    /* Individual doctor card styling */
    .card-doctor {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-doctor:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .card-doctor .header {
      position: relative;
    }

    .card-doctor .header img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-bottom: 1px solid #e0e0e0;
    }

    .card-doctor .header .meta {
      position: absolute;
      top: 10px;
      right: 10px;
      display: flex;
      gap: 8px;
    }

    .card-doctor .header .meta a {
      color: #ffffff;
      background: #00d4ff;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 18px;
      transition: background 0.3s ease;
    }

    .card-doctor .header .meta a:hover {
      background: #007bff;
    }

    .card-doctor .body {
      padding: 20px;
      text-align: center;
    }

    .card-doctor .body p {
      font-weight: bold;
      font-size: 1.3rem;
      margin-bottom: 8px;
      color: #333;
    }

    .card-doctor .body span {
      display: block;
      font-size: 1.1rem;
      color: #6c757d;
      margin-bottom: 15px;
    }

    .card-doctor .body .rating {
      margin: 15px 0;
      font-size: 1rem;
      color: #f39c12;
    }

    .card-doctor .body .rating .stars {
      font-family: Arial, sans-serif;
    }

    .card-doctor .body .rating .rating-value {
      font-size: 0.9rem;
      color: #6c757d;
    }

    .card-doctor .btn-readmore {
      display: inline-block;
      background: #007bff;
      color: #ffffff;
      padding: 10px 20px;
      border-radius: 20px;
      text-decoration: none;
      font-size: 1rem;
      transition: background 0.3s ease, transform 0.3s ease;
    }

    .card-doctor .btn-readmore:hover {
      background: #0056b3;
      transform: scale(1.05);
    }

    /* Filter dropdown styling */
    .filter-container {
    position: absolute;
    right: 70px;
    top: 120px;
    background: #ffffff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 10;
    }

    /* Dropdown select styling */
    .filter-container select {
    padding: 12px;
    font-size: 1.1rem;
    border: 1px solid #007bff;
    border-radius: 8px;
    outline: none;
    background-color: #f8f9fa;
    width: 200px;
    transition: all 0.3s ease; /* Smooth transition for hover and focus */
    }

    /* Hover effect for dropdown */
    .filter-container select:hover {
    border-color: #0056b3;
    background-color: #e6f0ff;
    }

    /* Focus effect for dropdown */
    .filter-container select:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Blue glow on focus */
    }

    /* Add a subtle hover effect to the dropdown items */
    .filter-container select option:hover {
    background-color: #f0f8ff;
    }


    /* Modal Styling */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: #ffffff;
  padding: 30px;
  border-radius: 12px;
  width: 400px;
  position: relative;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.modal .close {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 24px;
  color: #333;
  cursor: pointer;
}

.modal .close:hover {
  color: #007bff;
}

/* Style for rating section */
#modalRating {
  margin-top: 20px;
}

#rating {
  width: 100%;
  padding: 10px;
  font-size: 1rem;
  border: 1px solid #007bff;
  border-radius: 8px;
  outline: none;
  background-color: #f8f9fa;
}

#submitRating {
  display: block;
  background-color: #007bff;
  color: white;
  padding: 10px;
  border-radius: 20px;
  width: 100%;
  margin-top: 10px;
  cursor: pointer;
}

#submitRating:hover {
  background-color: #0056b3;
}


    /* Responsive Design */
    @media (max-width: 992px) {
      .doctor-container {
        grid-template-columns: repeat(2, 1fr); /* 2 cards per row */
      }
    }

    @media (max-width: 768px) {
      .doctor-container {
        grid-template-columns: 1fr; /* 1 card per row */
      }
    }
  </style>
</head>
<body>



<div class="page-header">
  <h1>Our Doctors</h1>
  <p>Meet our team of highly qualified specialists</p>
</div>

<!-- Filter Section -->
<div class="filter-container">
  <select id="specializationFilter">
    <option value="all">All Specializations</option>
    @foreach ($doctors->pluck('doctor.specialization')->unique() as $specialization)
      @if ($specialization)
        <option value="{{ $specialization }}">{{ $specialization }}</option>
      @endif
    @endforeach
  </select>
</div>

<!-- Doctor Cards Section -->
<div class="doctor-container" id="doctorContainer">
@foreach ($doctors as $doctor)
  <div class="card-doctor" data-specialization="{{ $doctor->doctor->specialization ?? 'N/A' }}">
    <div class="header">
      <img src="{{ $doctor->picture ? asset('uploads/profile/' . $doctor->picture) : asset('default-profile.png') }}" alt="Doctor Image">
      <div class="meta">
        <a href="tel:{{ $doctor->doctor->phone ?? '#' }}"><span class="mai-call"></span></a>
        <a href="https://wa.me/{{ $doctor->doctor->phone ?? '#' }}"><span class="mai-logo-whatsapp"></span></a>
      </div>
    </div>
    <div class="body">
      <p>{{ $doctor->doctor->name ?? 'N/A' }}</p>
      <span>{{ $doctor->doctor->specialization ?? 'N/A' }}</span>
      <div class="rating">
        <span class="stars">
          {{ str_repeat('★', floor($doctor->doctor->rating ?? 0)) }}
          {{ str_repeat('☆', 5 - floor($doctor->doctor->rating ?? 0)) }}
        </span>
        <span class="rating-value">({{ $doctor->doctor->rating ?? 'N/A' }})</span>
      </div>
      <button class="btn-readmore" data-id="{{ $doctor->id }}">Read More</button>
    </div>
  </div>
@endforeach
</div>

<!-- Modal for doctor info -->
<div id="doctorModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 id="modalDoctorName"></h2>
    <p id="modalSpecialization"></p>
    <p id="modalPhone"></p>
    <div id="modalRating">
      <label for="rating">Rate this Doctor:</label>
      <select id="rating">
        <option value="1">★</option>
        <option value="2">★★</option>
        <option value="3">★★★</option>
        <option value="4">★★★★</option>
        <option value="5">★★★★★</option>
      </select>
      <span id="ratingCount">(0 Ratings)</span>
      <button id="submitRating" data-id="">Submit</button>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('doctorModal');
    const closeModal = document.querySelector('.modal .close');
    const doctorCards = document.querySelectorAll('.btn-readmore');
    const submitRating = document.getElementById('submitRating');
    const ratingInput = document.getElementById('rating');
    const doctorIdInput = document.getElementById('submitRating');

    // Event listener for the "Read More" button
    doctorCards.forEach(button => {
        button.addEventListener('click', function () {
            const doctorId = this.getAttribute('data-id');

            // Send the fetch request to get the doctor details
            fetch(`/fetch-doctor/${doctorId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Doctor not found');
                    }
                    return response.json();
                })
                .then(data => {
                    // Populate modal with doctor data
                    document.getElementById('modalDoctorName').textContent = data.name;
                    document.getElementById('modalSpecialization').textContent = `Specialization: ${data.specialization}`;
                    document.getElementById('modalPhone').textContent = `Phone: ${data.phone}`;
                    document.getElementById('ratingCount').textContent = `(${data.rating_count} Ratings)`;
                    document.getElementById('rating').value = data.rating;
                    submitRating.setAttribute('data-id', doctorId); // Set doctor ID in the button

                    // Show modal
                    modal.style.display = 'flex';
                })
                .catch(error => {
                    alert('Error fetching doctor details: ' + error.message);
                    modal.style.display = 'none'; // Hide modal on error
                });
        });
    });

    // Close modal
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none'; // Close modal
    });

    
 submitRating.addEventListener('click', function () {
    const doctorId = this.getAttribute('data-id');
    const rating = ratingInput.value;

    console.log('Submitting rating for doctorId:', doctorId, 'Rating:', rating);

    // Send POST request with fetch (no jQuery)
    fetch('/doctorinfo/rate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            doctor_id: doctorId,
            rating: rating
        })
    })
    .then(response => {
        if (response.status === 302) {
            // Handle the redirect (user needs to log in)
            window.location.href = '/login';  // Adjust URL to your login page
            throw new Error('Redirecting to login');  // Prevent further processing
        }

        // Attempt to parse the JSON response
        return response.text();  // Read the response as text
    })
    .then(text => {
        try {
            const data = JSON.parse(text);  // Attempt to parse it as JSON
            console.log('Response Data:', data);  // Log the parsed response
            if (data.message) {
                alert(data.message);  // Show success message
                modal.style.display = 'none'; // Close the modal after submitting the rating
            } else if (data.error) {
                alert(data.error); // Show error message if no message is present
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
            alert('Error processing response. Please try again later.');
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        alert('Error submitting rating. Please try again later.');
    });
});




    // Specialization filter
    const filterDropdown = document.getElementById('specializationFilter');
    const doctorContainer = document.getElementById('doctorContainer');
    const doctorCardsAll = doctorContainer.querySelectorAll('.card-doctor');

    filterDropdown.addEventListener('change', function () {
        const selectedSpecialization = this.value;

        doctorCardsAll.forEach(card => {
            const specialization = card.getAttribute('data-specialization');
            if (selectedSpecialization === 'all' || specialization === selectedSpecialization) {
                card.style.display = ''; // Show the card
            } else {
                card.style.display = 'none'; // Hide the card
            }
        });
    });
});
</script>

@include('admin.script')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
