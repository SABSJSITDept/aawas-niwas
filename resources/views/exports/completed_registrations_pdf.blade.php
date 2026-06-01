<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Completed Registrations Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        @page {
            margin: 15mm;
        }
        .status-completed {
            color: #28a745;
            font-weight: bold;
        }
        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }
        .status-check-out {
            color: #17a2b8;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Completed Registrations Export</h1>
        <p>Generated on: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Total Records: {{ count($pdfData) }}</p>
    </div>

    @if(count($pdfData) > 0)
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Booking ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Total Persons</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pdfData as $row)
                <tr>
                    <td>{{ ucfirst($row['type'] ?? '') }}</td>
                    <td>{{ $row['booking_id'] ?? '' }}</td>
                    <td>{{ $row['name'] ?? '' }}</td>
                    <td>{{ $row['phone'] ?? '' }}</td>
                    <td>{{ $row['city'] ?? '' }}</td>
                    <td>{{ $row['state'] ?? '' }}</td>
                    <td>{{ $row['check_in_date'] ?? '' }}</td>
                    <td>{{ $row['check_out_date'] ?? '' }}</td>
                    <td class="text-center">{{ $row['total_persons'] ?? '' }}</td>
                    <td class="status-{{ strtolower(str_replace([' ', '-'], '', $row['status'] ?? '')) }}">
                        {{ ucfirst($row['status'] ?? '') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center" style="margin: 50px 0;">
            <p>No completed registrations found.</p>
        </div>
    @endif

    <div style="margin-top: 30px; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px;">
        <p>This report contains {{ count($pdfData) }} completed registration records as of {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Status Legend: <span class="status-completed">Completed</span> | <span class="status-rejected">Rejected</span> | <span class="status-checkout">Check-out</span></p>
    </div>
</body>
</html>