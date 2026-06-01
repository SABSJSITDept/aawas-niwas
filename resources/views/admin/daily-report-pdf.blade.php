<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Stay Report</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2> Daily Stay Report - {{ $today }}</h2>

    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Total Persons Staying</th>
            </tr>
        </thead>
        <tbody>
          
            <tr>
                <td> Family Bookings</td>
                <td>{{ $family }}</td>
            </tr>
            <tr>
                <td> Group Bookings</td>
                <td>{{ $group }}</td>
            </tr>
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>{{ $total }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
