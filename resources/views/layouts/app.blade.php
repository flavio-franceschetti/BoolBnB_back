<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>
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