<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Check-in Report PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        } 
        th, td {
            border: 1px solid #333;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        h3 {
            text-align: center;
        }
    </style>
</head>
<body>

    <h3>Date-wise Check-in and Check-out Report</h3>

    @if($from && $to)
        <p><strong>From:</strong> {{ $from }} | <strong>To:</strong> {{ $to }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Check-ins</th>
                <th>Check-outs</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $index => $entry)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($entry['date'])->format('d M Y') }}</td>
                    <td>{{ $entry['checkin'] }}</td>
                    <td>{{ $entry['checkout'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
