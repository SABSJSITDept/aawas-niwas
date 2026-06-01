<!DOCTYPE html>
<html>
<head>
    <title>परिवहन रिपोर्ट ({{ $selectedDate }})</title>
    <style>
        body { font-family: sans-serif; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f3f3f3;
        }
        h2 {
            text-align: center;
            margin-bottom: 0;
        }
        p {
            text-align: center;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <h2>parivahan report</h2>
    <p> Date: {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Hotel Name</th>
                <th> Total Staying</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $row)
                <tr>
                    <td>{{ $row['hotel_name'] }}</td>
                    <td>{{ $row['total_staying'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
