<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Check-out Registrations Export</title>
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
        .status-checkout {
            color: #ff6b6b;
            font-weight: bold;
        }
        .type-family {
            color: #007bff;
            font-weight: bold;
        }
        .type-group {
            color: #28a745;
            font-weight: bold;
        }
        .highlight-box {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8a80 100%);
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Check-out Registrations Export</h1>
        <p>Generated on: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Total Records: {{ count($pdfData) }}</p>
    </div>

    @if(count($pdfData) > 0)
        <div class="highlight-box">
            <strong>Summary Report</strong>
            @php
                $familyCount = collect($pdfData)->where('type', 'family')->count();
                $groupCount = collect($pdfData)->where('type', 'group')->count();
                $totalPersons = collect($pdfData)->sum('total_persons');
            @endphp
            <p style="margin: 5px 0;">Family Bookings: {{ $familyCount }} | Group Bookings: {{ $groupCount }} | Total Persons: {{ $totalPersons }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Booking ID</th>
                    <th>Name</th>
                    <th>Father Name</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Total Persons</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pdfData as $row)
                <tr>
                    <td class="type-{{ strtolower($row['type'] ?? '') }}">{{ ucfirst($row['type'] ?? '') }}</td>
                    <td><strong>{{ $row['booking_id'] ?? '' }}</strong></td>
                    <td>{{ $row['name'] ?? '' }}</td>
                    <td>{{ $row['father_name'] ?? '' }}</td>
                    <td>{{ $row['phone'] ?? '' }}</td>
                    <td>{{ $row['city'] ?? '' }}</td>
                    <td>{{ $row['state'] ?? '' }}</td>
                    <td>{{ $row['check_in_date'] ?? '' }}</td>
                    <td>{{ $row['check_out_date'] ?? '' }}</td>
                    <td class="text-center"><strong>{{ $row['total_persons'] ?? '' }}</strong></td>
                    <td class="status-checkout">{{ ucfirst($row['status'] ?? '') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center" style="margin: 50px 0;">
            <p>No check-out registrations found.</p>
        </div>
    @endif

    <div style="margin-top: 30px; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px;">
        <p>This report contains {{ count($pdfData) }} checked-out registration records as of {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Type Legend: <span class="type-family">Family Booking</span> | <span class="type-group">Group Booking</span></p>
        <p>All listed registrations have successfully completed their stay and checked out.</p>
    </div>
</body>
</html>