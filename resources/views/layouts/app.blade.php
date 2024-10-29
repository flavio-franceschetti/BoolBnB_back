<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- tomtom cdn --}}
    <link rel="stylesheet" type="text/css"
        href="https://api.tomtom.com/maps-sdk-for-web/cdn/plugins/SearchBox/3.1.3-public-preview.0/SearchBox.css" />
    <!-- includes the Braintree JS client SDK -->
    <script src="https://js.braintreegateway.com/web/dropin/1.43.0/js/dropin.min.js"></script>
    {{-- chart js per statistiche  --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- includes jQuery -->
    <script src="http://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.1.2-public-preview.15/services/services-web.min.js">
    </script>
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/plugins/SearchBox/3.1.3-public-preview.0/SearchBox-web.js">
    </script>
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.12.0/maps/maps-web.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.12.0/maps/maps.css" />
    <link rel="icon" type="image/svg+xml" href="/logo_bnb.png" />
    <!-- Includi Font Awesome senza integrità -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Indie+Flower&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <title>BoolBnb</title>
    <!-- Usando Vite -->
    @vite(['resources/js/app.js'])
</head>

<body>
    @include('admin.partials.header')
    @if (Auth::check())
        <div class="container-fluid h-100">
            <!-- Header -->
            <header class="header col-12">
                @include('admin.partials.header')
            </header>
            <div class="row h-100">

                <!-- Corpo principale -->
                <div class="col-12 d-flex h-100">
                    <!-- Aside -->
                    <aside class="aside d-none d-md-block ">
                        @include('admin.partials.aside')
                    </aside>

                    <!-- Content -->
                    <main class="content">
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
    @endif
    @yield('content')
</body>

</html>
