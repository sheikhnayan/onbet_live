<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('/images/icon_main.PNG') }}"/>
    <script src="https://kit.fontawesome.com/efec89e16a.js" crossorigin="anonymous"></script>
    <title>Betus</title>

    @include('frontend.partials.styleFiles')
    
    <style>
        @media (max-width: 575px){
            .matchTournamentLiveWrap {margin: 0;}
        }
    </style>
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8NV9VS1V9Y"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-8NV9VS1V9Y');
    </script>

</head>
    <body>

    <!-- Menu -->
    @yield("mainMenu")
    <!-- Menu -->

    <!-- Main Content -->
    @yield('content')
    <!-- Main Content -->

    <!-- footer top and bottom -->
    @yield("main-footer")
    <!-- footer top and bottom -->

    <!-- Script -->
    @yield("main-script")

    @yield("scriptExtra")
    <!-- Script -->
    </body>
</html>
