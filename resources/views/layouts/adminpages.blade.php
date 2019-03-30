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
    <script src="//cdn.ckeditor.com/4.9.2/standard/ckeditor.js"></script>
    <script src="{{asset('js/admin.js')}}"></script>
    @yield('scripts')
</body>
</html>
