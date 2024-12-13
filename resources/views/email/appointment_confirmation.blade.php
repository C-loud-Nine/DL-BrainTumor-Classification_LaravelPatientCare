<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .header {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 15px 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .header h1 {
            font-size: 1.8em;
            margin: 0;
        }

        .content {
            padding: 20px;
        }

        .content p {
            margin: 10px 0;
            font-size: 1rem;
        }

        .content p strong {
            color: #333;
        }

        .highlight {
            color: #4CAF50;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #555;
        }

        .cta {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
            text-align: center;
        }

        .cta:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Appointment Confirmation</h1>
        </div>
        <div class="content">
            <p>Dear <span class="highlight">{{ $name }}</span>,</p>
            <p>We are pleased to inform you that your appointment with <strong>Dr. {{ $doctor }}</strong> has been successfully confirmed.</p>
            <p><strong>Details of your appointment:</strong></p>
            <ul>
                <li><strong>Date:</strong> <span class="highlight">{{ $date }}</span></li>
                <li><strong>Message:</strong> {{ $message_content }}</li>
            </ul>
            <p>Please arrive at least 15 minutes before your scheduled time to complete any necessary paperwork. If you have any questions, feel free to contact our support team.</p>
            <a href="http://127.0.0.1:8000/userapp" class="cta">View Appointment Details</a>
        </div>
        <div class="footer">
            <p>&copy; 2024 One Health +. All rights reserved.</p>
            <p>For inquiries, please contact us at <a href="mailto:support@onehealth-plus.com">support@onehealth-plus.com</a>.</p>
        </div>
    </div>
</body>
</html>
