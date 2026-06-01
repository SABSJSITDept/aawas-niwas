<!DOCTYPE html>
<html>
<head>
    <title>Hotel Room Booking Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Hotel Room Booking Report</h2>

<table border="1" width="100%" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Hotel Name</th>
            <th>Room Number</th>
            <th>Booked By</th>
            <th>Mobile Number</th> {{-- ✅ New column --}}
            <th>Check-in Date</th>
            <th>Check-out Date</th>
            <th>Total Persons</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reportData as $data)
        <tr>
            <td>{{ $data['hotel_name'] }}</td>
            <td>{{ $data['room_number'] }}</td>
            <td>{{ $data['booked_by'] }}</td>
            <td>{{ $data['mobile_number'] ?? 'N/A' }}</td> {{-- ✅ New column --}}
            <td>{{ $data['check_in_date'] }}</td>
            <td>{{ $data['check_out_date'] }}</td>
            <td>{{ $data['total_persons'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
