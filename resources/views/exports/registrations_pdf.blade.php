<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registrations Export</title>
    <style>
        * {
            font-family: DejaVuSans;
        }
        body {
            font-family: DejaVuSans;
            font-size: 11px;
            margin: 15px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 4px;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        th {
            background-color: #e8e8e8;
            font-weight: bold;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        @page {
            margin: 10mm;
            size: A4 landscape;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pending Registrations Export</h1>
        <p>Generated on: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Total Records: {{ count($pdfData) }}</p>
    </div>

    @if(count($pdfData) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Type</th>
                    <th style="width: 10%;">Booking ID</th>
                    <th style="width: 12%;">Name</th>
                    <th style="width: 10%;">Phone</th>
                    <th style="width: 10%;">City</th>
                    <th style="width: 10%;">State</th>
                    <th style="width: 10%;">Aanchal</th>
                    <th style="width: 7%;">Check-in Date</th>
                    <th style="width: 6%;">Check-in Time</th>
                    <th style="width: 7%;">Check-out Date</th>
                    <th style="width: 6%;">Check-out Time</th>
                    <th style="width: 8%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pdfData as $row)
                <tr>
                    <td>{{ $row['type'] ?? '' }}</td>
                    <td>{{ $row['booking_id'] ?? '' }}</td>
                    <td>{{ $row['name'] ?? '' }}</td>
                    <td>{{ $row['phone'] ?? '' }}</td>
                    <td>{{ $row['city'] ?? '' }}</td>
                    <td>{{ $row['state'] ?? '' }}</td>
                    <td>{{ $row['aanchal'] ?? '' }}</td>
                    <td>{{ $row['check_in_date'] ?? '' }}</td>
                    <td>{{ $row['check_in_time'] ?? '' }}</td>
                    <td>{{ $row['check_out_date'] ?? '' }}</td>
                    <td>{{ $row['check_out_time'] ?? '' }}</td>
                    <td class="text-center">{{ $row['total_persons'] ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center" style="margin: 50px 0;">
            <p>No pending registrations found.</p>
        </div>
    @endif

    <div style="margin-top: 20px; font-size: 9px; color: #666; border-top: 1px solid #ddd; padding-top: 10px;">
        <p><strong>Note:</strong> This PDF export shows data with limited Unicode support. For better rendering of Hindi names, please use the Excel export which displays Hindi text correctly.</p>
        <p>Report contains {{ count($pdfData) }} registration records as of {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>