<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.12.0/maps/maps-web.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.12.0/maps/maps.css" />
    <link rel="icon" type="image/svg+xml" href="/logo_bnb.png" />
    <title>{{ config('app.name', 'BoolBnB') }}</title>
    <!-- Usando Vite -->
    @vite(['resources/js/app.js'])
</head>

<body>

    @include('admin.partials.header')

    <div class="wrapper d-flex">
        @if (Auth::check())
        @include('admin.partials.aside')
        @endif
        <div class="main-content p-4">
            @yield('content')
        </div>
    </div>
</body>

</html>
