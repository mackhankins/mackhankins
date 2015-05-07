@if(!empty($meta['title']))
    <title>{{ $meta['title'] . ' - Mack Hankins' }}</title>
@else
    <title>Mack Hankins</title>
@endif
    <!--Facebook Metadata /-->
@if(!empty($meta['image']))
    <meta property="og:image" content="{{ url($meta['image']) }}"/>
@else
    <meta property="og:image" content="{{ url('/mackhankins-social.jpg') }}"/>
@endif
@if(!empty($meta['description']))
    <meta property="og:description" content="{{ str_limit($meta['description'], $limit = 100, $end = '...') }}"/>
@else
    <meta property="og:description" content="A personal website built on Laravel 5."/>
@endif
@if(!empty($meta['title']))
    <meta property="og:title" content="{{ $meta['title'] }}"/>
@else
    <meta property="og:title" content="Mack Hankins"/>
@endif
    <!--Google+ Metadata /-->
@if(!empty($meta['title']))
    <meta itemprop="name" content="{{ $meta['title'] }}">
@else
    <meta itemprop="name" content="Mack Hankins">
@endif
@if(!empty($meta['description']))
    <meta itemprop="description" content="{{ str_limit($meta['description'], $limit = 100, $end = '...') }}"/>
@else
    <meta itemprop="description" content="A personal website built on Laravel 5."/>
@endif
@if(!empty($meta['image']))
    <meta itemprop="image" content="{{ url($meta['image']) }}"/>
@else
    <meta itemprop="image" content="{{ url('/mackhankins-social.jpg') }}"/>
@endif
    <!-- Twitter Metadata /-->
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:site" content="@mackhankins"/>
@if(!empty($meta['title']))
    <meta name="twitter:title" content="{{ $meta['title'] }}">
@else
    <meta name="twitter:title" content="Mack Hankins">
@endif
@if(!empty($meta['description']))
    <meta name="twitter:description" content="{{ str_limit($meta['description'], $limit = 100, $end = '...') }}"/>
@else
    <meta name="twitter:description" content="A personal website built on Laravel 5. 5."/>
@endif
@if(!empty($meta['image']))
    <meta name="twitter:image" content="{{ url($meta['image']) }}"/>
@else
    <meta name="twitter:image" content="{{ url('/mackhankins-social.jpg') }}"/>
@endif
    <meta name="twitter:domain" content="mackhankins.com">