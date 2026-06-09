<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Available Rooms All Hotels</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th { background-color: #f4f4f4; padding: 8px; text-align: left; }
        td { padding: 6px; }
        .hotel-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; background-color: #e9ecef; padding: 5px; border-left: 4px solid #000; }
        .totals-row { font-weight: bold; background-color: #f8f9fa; }
        .summary-table th { background-color: #d1ecf1; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Hotel Availability Report</h2>
        <p>Generated on: {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <h4>Overall Summary</h4>
    <table class="summary-table">
        <tr>
            <th>Total Rooms</th>
            <th>Total Capacity</th>
            <th>Total Booked</th>
            <th>Total Available</th>
        </tr>
        <tr>
            <td style="text-align: center;">{{ $totals['total_rooms'] }}</td>
            <td style="text-align: center;">{{ $totals['total_capacity'] }}</td>
            <td style="text-align: center; color: red;">{{ $totals['total_booked'] }}</td>
            <td style="text-align: center; color: green;">{{ $totals['total_available'] }}</td>
        </tr>
    </table>

    @foreach($data as $hotel)
    <div class="hotel-title">{{ $hotel['hotel_name'] }}</div>
    <table>
        <thead>
            <tr>
                <th>Room No</th>
                <th>Category</th>
                <th>Floor</th>
                <th>Beds</th>
                <th>Extra Cap.</th>
                <th>Total Cap.</th>
                <th>Booked</th>
                <th>Available</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hotel['rooms'] as $room)
            <tr>
                <td>{{ $room['room_no'] }}</td>
                <td>{{ $room['category'] }}</td>
                <td>{{ $room['floor'] }}</td>
                <td>{{ $room['beds'] }}</td>
                <td>{{ $room['extra_capacity'] }}</td>
                <td>{{ $room['total_capacity'] }}</td>
                <td>{{ $room['booked'] }}</td>
                <td>{{ $room['available'] }}</td>
            </tr>
            @endforeach
            <tr class="totals-row">
                <td colspan="5" style="text-align: right;">Total:</td>
                <td>{{ $hotel['totals']['total_capacity'] }}</td>
                <td>{{ $hotel['totals']['total_booked'] }}</td>
                <td>{{ $hotel['totals']['total_available'] }}</td>
            </tr>
        </tbody>
    </table>
    @endforeach

</body>
</html>
