<!DOCTYPE html>
<html>
<head>
    <title>Room Report</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #000; padding: 6px; text-align: center; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Room Report</h2>
    <table>
        <thead>
            <tr>
                <th>NAME</th>
                <th>PHONE</th>
                <th>TOTAL PERSONS</th>
                <th>CHECK IN</th>
                <th>CHECK IN TIME</th>
                <th>CHECK OUT</th>
                <th>CHECK OUT TIME</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['phone'] }}</td>
                <td>{{ $row['total_persons'] }}</td>
                <td>{{ $row['check_in_date'] }}</td>
                <td>{{ $row['check_in_time'] ?? '-' }}</td>
                <td>{{ $row['check_out_date'] }}</td>
                <td>{{ $row['check_out_time'] ?? '-' }}</td>
            </tr>
            @endforeach

            <!-- ✅ Total Row -->
            <tr style="font-weight: bold; background-color: #f2f2f2;">
                <td colspan="2">TOTAL</td>
                <td>{{ $totalPersons }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
