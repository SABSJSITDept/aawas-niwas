<!DOCTYPE html>
<html>
<head>
    <title>Pending Room Allotment</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">❌ Room Not Allotted Report</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Total Persons</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $d)
            <tr>
                <td>{{ $d['booking_id'] }}</td>
                <td>{{ $d['name'] }}</td>
                <td>{{ $d['phone'] }}</td>
                <td>{{ $d['total_persons'] }}</td>
                <td>{{ $d['check_in'] }}</td>
                <td>{{ $d['check_out'] }}</td>
                <td>{{ $d['type'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
