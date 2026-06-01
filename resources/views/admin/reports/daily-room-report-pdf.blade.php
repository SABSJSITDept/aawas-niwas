<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daily Room Report - {{ $today }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        h2 { text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Daily Room Report - {{ $today }}</h2>

    <table>
        <tr><th>Total Rooms</th><td>{{ $totalRooms }}</td></tr>
        <tr><th>Booked Rooms</th><td>{{ $bookedRooms }}</td></tr>
        <tr><th>Empty Rooms</th><td>{{ $emptyRooms }}</td></tr>
        <tr><th>Total Capacity</th><td>{{ $totalCapacity }}</td></tr>
        <tr><th>Booked Capacity</th><td>{{ $bookedCapacity }}</td></tr>
        <tr><th>Available Capacity</th><td>{{ $availableCapacity }}</td></tr>
        <tr><th>Total Guests Today</th><td>{{ $totalGuests }}</td></tr>
        <tr><th>Check-ins Today</th><td>{{ $checkInCount }}</td></tr>
        <tr><th>Check-outs Today</th><td>{{ $checkOutCount }}</td></tr>
    </table>
</body>
</html>
