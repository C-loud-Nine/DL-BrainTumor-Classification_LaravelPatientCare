<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
            border: 1px solid #ddd;
        }

        h1 {
            color: #4CAF50;
            font-size: 1.8em;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            margin: 10px 0;
            font-size: 1rem;
        }

        strong {
            color: #333;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #777;
            font-size: 0.9rem;
        }

        .highlight {
            color: #4CAF50;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Appointment Confirmation</h1>
        <p>Dear <span class="highlight">{{ $name }}</span>,</p>
        <p>Your appointment with Dr. <span class="highlight">{{ $doctor }}</span> has been confirmed.</p>
        <p><strong>Date:</strong> <span class="highlight">{{ $date }}</span></p>
        <p><strong>Message:</strong> {{ $message_content }}</p>
        <p>Thank you for choosing <strong>One Health +</strong>. We look forward to serving you.</p>
        <div class="footer">
            <p>&copy; 2024 One Health +. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
