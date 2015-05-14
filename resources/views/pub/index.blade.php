@extends('layouts.pub.master')

@section('styles')

@endsection

@section('page-header')
    <div class="top70"></div>
@endsection

@section('content')
    <div class="row">
        <?php $i = 0; ?>
        @foreach($posts as $post)
            <div class="col-md-4 top17">
                <img src="{!! URL::to('images/medium/'.$post->featuredimage) !!}" alt="{{$post->title}}"
                     class="post-image bordered">

                <div class=" portlet light bordered">
                    <div class="portlet-title front">
                        <div class="caption caption-subject">
                            <a href="{!! action('Pub\BlogController@single', $post->slug) !!}">{!! $post->title !!}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 text-left-xs text-left-md text-left subtext">
                            <i class="icon-hourglass"></i>
                            {!! \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->diffForHumans() !!}
                        </div>
                        <div class="col-xs-6 text-right-xs text-right-md text-right subtext">
                            <i class="icon-bubbles"></i>
                            <a href="{!! action('Pub\BlogController@single', $post->slug) !!}#disqus_thread"></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php $i ++; ?>
            @if($i == 3)
    </div>
    <div class="row">
        <?php $i = 0; ?>
        @endif
        @endforeach
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES * * */
        var disqus_shortname = '{!! env("DISQUS_NAME") !!}';

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
            var s = document.createElement('script');
            s.async = true;
            s.type = 'text/javascript';
            s.src = '//' + disqus_shortname + '.disqus.com/count.js';
            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
        }());
        @include('partials.pub._analytics')
    </script>
@endsection