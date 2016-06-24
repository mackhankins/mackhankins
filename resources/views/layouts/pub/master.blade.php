<!DOCTYPE html>
<!--[if IE 8]><html lang="en" class="ie8 no-js"><![endif]-->
<!--[if IE 9]><html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--><html lang="en"><!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    @include('partials.pub._meta')

    <link rel="icon" href="{{ URL::asset('favicon.png') }}"/>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all">
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/app.css') }}">
    @yield('styles')
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body>
@include('partials.pub._navbar')
@yield('page-header')
<!-- BEGIN CONTAINER -->
<div class="container">
    @yield('content')
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
@include('partials.pub._footer')
<!-- END FOOTER -->
<script src="{{ elixir('js/app.js') }}"></script>
@yield('scripts')
</body>
<!-- END BODY -->
</html>