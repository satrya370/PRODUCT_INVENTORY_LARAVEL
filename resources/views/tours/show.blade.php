<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $tour->title }}</title>
</head>
<body>
    <h1>{{ $tour->title }}</h1>
    <p>{{ $tour->description }}</p>
    <ul>
        <li>Duration: {{ $tour->duration_minutes }} minutes</li>
        <li>Price: Rp{{ $tour->base_price }}</li>
        <li>Capacity: {{ $tour->default_capacity }} seats</li>
        <li>Status: {{ $tour->status }}</li>
    </ul>
</body>
</html>
