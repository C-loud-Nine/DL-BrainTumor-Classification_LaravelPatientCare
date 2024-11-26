<x-header />

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
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
      <a href="profile.html" class="btn-readmore">Read More</a>
    </div>
  </div>
  @endforeach
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const filterDropdown = document.getElementById('specializationFilter');
    const doctorContainer = document.getElementById('doctorContainer');
    const doctorCards = doctorContainer.querySelectorAll('.card-doctor');

    // Event listener for the filter dropdown
    filterDropdown.addEventListener('change', function () {
      const selectedSpecialization = this.value;

      doctorCards.forEach(card => {
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

</body>
</html>

<x-footer />
