<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/cs_icon.png">

    <title>{{ $title ?? 'Creativity' }}</title>

    @vite(['resources/styles/app.scss', 'resources/js/app.js'])
</head>
<body>
{!! $slot !!}
</body>
</html>
