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

    <div class="loader" id="loader">
        <h1>BoolBnB</h1>
        <div class="spinner"></div>
        <i class="fas fa-key" style="color: #379c4e; font-size: 30px; margin-top: 15px; z-index: 1;"></i>
    </div>
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

<style>
    body {
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    .loader {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.8);
        /* Sfondo scuro */
    }

    .loader h1 {
        color: white;
        font-size: 48px;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }

    .spinner {
        border: 8px solid rgba(255, 255, 255, 0.3);
        border-left-color: white;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
        z-index: 1;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .house {
        position: absolute;
        bottom: 0;
        width: 80px;
        height: 80px;
        background-color: #fff;
        border: 2px solid #000;
        clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
    }

    /* Modificato per centrare le case */
    .house:nth-child(1) {
        left: 15%;
        animation: moveHouse 8s linear infinite;
    }

    .house:nth-child(2) {
        left: 35%;
        animation: moveHouse 6s linear infinite;
    }

    .house:nth-child(3) {
        left: 55%;
        animation: moveHouse 10s linear infinite;
    }

    .house:nth-child(4) {
        left: 75%;
        animation: moveHouse 7s linear infinite;
    }

    .house:nth-child(5) {
        left: 95%;
        animation: moveHouse 9s linear infinite;
    }

    /* Case animate sopra */
    .house-above {
        position: absolute;
        top: 0;
        width: 80px;
        height: 80px;
        background-color: #fff;
        border: 2px solid #000;
        clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
        animation: moveHouse 10s linear infinite;
    }

    .house-above:nth-child(1) {
        left: 10%;
        animation-duration: 8s;
    }

    .house-above:nth-child(2) {
        left: 30%;
        animation-duration: 6s;
    }

    .house-above:nth-child(3) {
        left: 50%;
        animation-duration: 10s;
    }

    .house-above:nth-child(4) {
        left: 70%;
        animation-duration: 7s;
    }

    .house-above:nth-child(5) {
        left: 90%;
        animation-duration: 9s;
    }
</style>
<script>
    function showLoader() {
        $('#loader').fadeIn();
    }

    function hideLoader() {
        $('#loader').fadeOut();
    }


    document.getElementById('loader').style.display = 'flex';

    // Nascondi il loader dopo 3 secondi (puoi cambiare il tempo come preferisci)
    setTimeout(function() {
        document.getElementById('loader').style.display = 'none';
    }, 3000);
</script>
