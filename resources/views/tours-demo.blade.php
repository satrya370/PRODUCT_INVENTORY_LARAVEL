<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TourFlow Demo</title>
</head>
<body>
    <h1>TourFlow Demo</h1>
    <ul>
        @foreach ($tours as $tour)
            <li>
                {{ $tour['name'] }} — {{ $tour['duration'] }} — Rp{{ $tour['price'] }}
            </li>
        @endforeach
    </ul>
</body>
</html>
