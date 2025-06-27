<!--
=========================================================
* Soft UI Dashboard Tailwind - v1.0.5
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard-tailwind
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png" />
        <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-iflab.png') }}" />
        <title>Informatics Lab - Inventory</title>
        <!--     Fonts and icons     -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- TEMPORARY: Tambahkan ini di <head> hanya untuk cek -->
        {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
        <!-- Nucleo Icons -->
        <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
        <!-- Popper -->
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <!-- Nepcha Analytics (nepcha.com) -->
        <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
        <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Main Styling -->
        <link rel="stylesheet" href="{{ asset('assets/css/soft-ui-dashboard-tailwind.css') }}?v={{ filemtime(public_path('assets/css/soft-ui-dashboard-tailwind.css')) }}">
    </head>

    <body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
        <!-- sidenav  -->
        @include('layouts.partials.navigation')
        <!-- end sidenav -->

        <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
            <!-- Navbar -->
            @include('layouts.partials.header')
            <!-- end Navbar -->

            <!-- cards -->
            @yield('content')
            <!-- end cards -->
        </main>
        
        {{-- @include('layouts.partials.footer') --}}
    </body>
    <!-- plugin for charts  -->
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}" async></script>
    <!-- plugin for scrollbar  -->
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>
    <!-- github button -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- main script file  -->
    <script src="{{ asset('assets/js/soft-ui-dashboard-tailwind.js') }}?v=1.0.5" async></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</html>
