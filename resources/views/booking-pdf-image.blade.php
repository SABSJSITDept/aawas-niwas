<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }
        html, body {
            margin: 0;
            padding: 0;
            width: 595.28pt;
            height: 841.89pt;
            overflow: hidden;
        }
        img {
            display: block;
            margin: 0;
            padding: 0;
            page-break-before: avoid;
            page-break-after: avoid;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
@if(!empty($imagePath) && file_exists($imagePath))
    @php
        $imgData = base64_encode(file_get_contents($imagePath));
        unlink($imagePath);
    @endphp
    <img src="data:image/png;base64,{{ $imgData }}" alt="Booking Confirmation" style="width: 595.28pt; height: 841.89pt;">
@endif
</body>
</html>
