<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{!! csrf_token() !!}"/>
    <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
    <main role="main">
    <!--page-->
    @yield('content')
    </main>
    @if(!is_null(Settings::getByKey('google-analitycs')->value))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{Settings::getByKey('google-analitycs')->value}}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', "{{Settings::getByKey('google-analitycs')->value}}");
    </script>
    @endif
    <script src="{{asset('js/app.js')}}"></script>
    @yield('scripts')
</body>
</html>
