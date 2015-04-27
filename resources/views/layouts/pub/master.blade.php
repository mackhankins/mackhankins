<!DOCTYPE html>
<!--[if IE 8]><html lang="en" class="ie8 no-js"><![endif]-->
<!--[if IE 9]><html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--><html lang="en"><!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>{!! Meta::meta('title') !!}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    {!! Meta::tagMetaProperty('site_name', 'Mack Hankins') !!}
    {!! Meta::tagMetaProperty('url', Request::url()) !!}
    {!! Meta::tagMetaProperty('locale', 'en_EN') !!}
    {!! Meta::tag('title') !!}
    {!! Meta::tag('description') !!}
    {!! Meta::tag('image') !!}
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all">
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/app.css') }}">
    @yield('styles')
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}"/>
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
<!-- BEGIN SCRIPTS -->
<script src="{{ elixir('js/app.js') }}"></script>
@yield('scripts')
<!-- END SCRIPTS -->
</body>
<!-- END BODY -->
</html>