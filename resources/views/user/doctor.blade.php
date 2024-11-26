<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctor Slideshow</title>
  <link rel="stylesheet" href="path-to-owl-carousel.css"> <!-- Include Owl Carousel CSS -->
  <style>
    /* Styling for the doctor cards */
    .card-doctor {
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      width: 320px; /* Set card width */
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin: auto; /* Center align cards */
    }

    .card-doctor:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }

    .card-doctor .header {
      position: relative;
    }

    .card-doctor .header img {
      width: 100%; /* Full width */
      height: 200px; /* Fixed height */
      object-fit: cover; /* Prevent distortion */
      display: block;
    }

    .card-doctor .header .meta {
      position: absolute;
      top: 10px;
      right: 10px;
    }

    .card-doctor .header .meta a {
      color: #fff;
      background: #00d4ff;
      border-radius: 50%;
      display: inline-block;
      width: 30px;
      height: 30px;
      line-height: 30px;
      text-align: center;
      margin-left: 5px;
      transition: background 0.3s ease;
    }

    .card-doctor .header .meta a:hover {
      background: #0099cc;
    }

    .card-doctor .body {
      padding: 20px; /* Added padding for a spacious look */
      text-align: center;
    }

    .card-doctor .body p {
      font-weight: bold;
      margin-bottom: 5px;
    }

    .card-doctor .body span {
      display: block;
      font-size: 0.9rem;
      color: #6c757d;
      margin-bottom: 10px;
    }

    .card-doctor .body .rating {
      margin: 10px 0;
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
      border-radius: 25px;
      text-decoration: none;
      font-size: 0.9rem;
      transition: background 0.3s ease, transform 0.3s ease;
    }

    .card-doctor .btn-readmore:hover {
      background: #0056b3;
      transform: scale(1.05);
    }

    .owl-carousel .item {
      display: flex;
      justify-content: center;
    }
  </style>
</head>
<body>

<!-- Doctor Slideshow -->
<div class="owl-carousel wow fadeInUp" id="doctorSlideshow">
  @foreach ($doctors as $doctor)
  <div class="item">
    <div class="card-doctor">
      <div class="header">
        <img src="{{ $doctor->picture ? asset('uploads/profile/' . $doctor->picture) : asset('default-profile.png') }}" alt="Doctor Image">
        <div class="meta">
          <a href="tel:{{ $doctor->doctor->phone ?? '#' }}"><span class="mai-call"></span></a>
          <a href="https://wa.me/{{ $doctor->doctor->phone ?? '#' }}"><span class="mai-logo-whatsapp"></span></a>
        </div>
      </div>
      <div class="body">
        <p class="text-xl mb-0">{{ $doctor->doctor->name ?? 'N/A' }}</p>
        <span class="text-sm text-grey">{{ $doctor->doctor->specialization ?? 'N/A' }}</span>
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
  </div>
  @endforeach
</div>

<!-- Include Owl Carousel JavaScript -->
<script src="path-to-jquery.js"></script> <!-- Include jQuery if not already -->
<script src="path-to-owl-carousel.js"></script> <!-- Include Owl Carousel JS -->
<script>
  // Initialize Owl Carousel
  $(document).ready(function(){
    $('#doctorSlideshow').owlCarousel({
      items: 3, // Show 3 cards at a time
      loop: true,
      margin: 15,
      autoplay: true,
      autoplayTimeout: 5000,
      autoplayHoverPause: true,
      responsive: {
        0: { items: 1 }, // 1 item on small screens
        768: { items: 2 }, // 2 items on medium screens
        1200: { items: 3 } // 3 items on large screens
      }
    });
  });
</script>
</body>
</html>
