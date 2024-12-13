<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MRI Report</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            color: #333;
            width: 100%;
            height: 100%;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }

        .header h1 {
            font-size: 24px;
            color: #007bff;
            margin: 0;
        }

        .header p {
            font-size: 14px;
            color: #555;
        }

        .info-block {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 10px;
        }

        .info-left, .info-right {
            width: 48%;
        }

        .info-left p, .info-right p {
            margin: 4px 0;
            line-height: 1.4;
            font-size: 14px;
        }

        .info-left strong, .info-right strong {
            color: #007bff;
        }

        .details-section {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 15px;
        }

        .image-container {
            width: 48%;
            text-align: center;
        }

        .image-container img {
            max-width: 100%;
            height: auto;
            max-height: 200px; /* Ensures consistent height */
        }

        .table-container {
            width: 48%;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .table-container th, .table-container td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table-container th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .table-container td {
            background-color: #f9f9f9;
        }

        .assessment, .suggestions {
            margin-top: 15px;
            font-size: 14px;
        }

        .assessment h3, .suggestions h3 {
            color: #007bff;
            margin-bottom: 5px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 15px;
        }

        .footer p {
            margin: 0;
        }

        @media (max-width: 768px) {
            .info-block, .details-section {
                flex-direction: column;
                align-items: center;
            }

            .info-left, .info-right, .image-container, .table-container {
                width: 100%;
                margin-bottom: 10px;
            }

            .header h1 {
                font-size: 20px;
            }

            .info-left p, .info-right p, .table-container th, .table-container td {
                font-size: 12px;
            }

            .table-container th, .table-container td {
                padding: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>One Health + Diagnostic Center</h1>
            <p>Official MRI Report</p>
        </div>

        <!-- Info Block Section -->
        <div class="info-block">
            <div class="info-left">
                <p><strong>Patient Name:</strong> {{ $user_name }}</p>
                <p><strong>Doctor:</strong> {{ $scanner_name }}</p>
                <p><strong>Report Type:</strong> {{ $report_class }}</p>
                <p><strong>Confidence Level:</strong> {{ $confidence }}%</p>
            </div>
            <div class="info-right">
                <p><strong>Report Date:</strong> {{ $created_at }}</p>
                <p><strong>Issued On:</strong> {{ $report_date }}</p>
                <p><strong>Follow-Up Date:</strong> {{ $follow_up_date }}</p>
            </div>
        </div>

        <!-- Image and Table Section -->
        <div class="details-section">
            <div class="image-container">
                @if($report_image)
                    <h3>Report Image</h3>
                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($report_image)) }}" alt="MRI Report Image"/>
                @else
                    <p>No Image Available</p>
                @endif
            </div>
            <div class="table-container">
                <h3>Test Details</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Test Details</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Report Class</strong></td>
                            <td>{{ $report_class }}</td>
                        </tr>
                        <tr>
                            <td><strong>Confidence Level</strong></td>
                            <td>{{ $confidence }}%</td>
                        </tr>
                        <tr>
                            <td><strong>Report Date</strong></td>
                            <td>{{ $created_at }}</td>
                        </tr>
                        <tr>
                            <td><strong>Time of Issue</strong></td>
                            <td>{{ $report_date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Assessment and Suggestions Section -->
        <div class="assessment">
            <h3>Assessment</h3>
            <p>{{ $assessment }}</p>
        </div>
        <div class="suggestions">
            <h3>Suggestions</h3>
            <p>{{ $suggestions }}</p>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>&copy; 2024 One Health + Diagnostic Center. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
