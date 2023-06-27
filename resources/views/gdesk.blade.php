<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ config('app.name') }}</title>
        <!--Tailwind app.css-->
        <!--<link rel="stylesheet" href=" /*mix('css/app.css')*/ " />-->
        <link rel="stylesheet" href="{{ mix('css/all.combined.css') . '?v=' . time() }}" />
        <link rel="icon" type="image/png" href="/img/logo.png">
        <!--<link rel="stylesheet" href="https://demo.productionready.io/main.css">-->
        <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=fd279767-cc7d-4d36-b145-2ede11de2c92" type="text/javascript"></script>
    </head>
    <body class="antialiased">
    <div id="app"></div>
    <script type="text/javascript" src="{{ mix('js/all.combined.js') . '?v=' . time() }}"></script>
    <script type="text/javascript" src="{{ mix('js/ymaps-jquery.js') . '?v=' . time() }}"></script>
    <script type="text/javascript" src="{{ mix('js/app.js') . '?v=' . time() }}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    </body>
</html>
