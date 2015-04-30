@extends('layouts.pub.master')

@section('styles')
    <link rel="stylesheet" href="{!! URL::asset('css/github.css') !!}">
@endsection

@section('page-header')
    @include('partials.pub._page-header')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 top17">
            @if(!empty($post->featuredimage))
                <img src="{{ URL::to('images/large/'.$post->featuredimage.$post->mimetype) }}"
                     alt="{{$post->title}}" class="post-image">
            @endif
            <div class="portlet light bordered">
                <div class="subtext">
                    <i class="icon-hourglass"></i>
                    {!! \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->toFormattedDateString() !!}
                    &nbsp;
                    <i class="icon-bubbles"></i>
                    <a href="{!! action('Pub\BlogController@single', $post->slug) !!}#disqus_thread"></a>
                </div>
                <div class="portlet-body bordered">{!! $post->pcontent !!}</div>
                <div id="disqus_thread"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function () {
            jQuery('pre').each(function (i, block) {
                hljs.highlightBlock(block);
            });
        });
        /* * * CONFIGURATION VARIABLES * * */
        var disqus_shortname = 'mackhankins';

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
            var dsq = document.createElement('script');
            dsq.type = 'text/javascript';
            dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            var s = document.createElement('script');
            s.async = true;
            s.type = 'text/javascript';
            s.src = '//' + disqus_shortname + '.disqus.com/count.js';
            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
        })();
    </script>
@endsection