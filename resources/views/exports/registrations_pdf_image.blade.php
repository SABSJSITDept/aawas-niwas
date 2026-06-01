<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registrations Export</title>
    <style>
        body {
            margin: 0;
            padding: 10px;
            background: white;
        }
        .container {
            width: 100%;
            text-align: center;
        }
        img {
            max-width: 100%;
            height: auto;
            display: block;
            page-break-inside: avoid;
            border: none;
        }
        @page {
            margin: 5mm;
            size: A4 landscape;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($imagePath && file_exists($imagePath))
            {{-- Convert image to base64 for embedding in PDF --}}
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($imagePath)) }}" alt="Registration Table Report">
            {{-- Clean up temp file after rendering --}}
            @php
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            @endphp
        @else
            <div style="text-align: center; padding: 50px; color: red;">
                <strong>Error: Unable to generate report image</strong>
                <p>Please try exporting as Excel format instead.</p>
            </div>
        @endif
    </div>
</body>
</html>
