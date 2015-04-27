@extends('layouts.pub.master')

@section('styles')
    <link rel="stylesheet" href="{!! URL::asset('css/github.css') !!}">
@endsection

@section('page-header')
    @include('partials.pub._page-header')
@endsection

@section('content')
    <div class="row">
        @foreach($posts as $post)
            <div class="col-md-12 top17">
                @if(!empty($post->featuredimage))
                    <img src="{{ URL::to('images/large/'.$post->featuredimage.$post->mimetype) }}"
                         alt="{{$post->title}}" class="post-image">
                @endif
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-speech"></i>
                            <span class="caption-subject bold uppercase"><a href="{!! action('Pub\BlogController@single', $post->slug) !!}">{!! $post->title !!}</a></span>
                        </div>
                    </div>
                    <div class="subtext">
                    <i class="icon-hourglass"></i>
                        {!! \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->toFormattedDateString() !!}
                    &nbsp;
                    <i class="icon-bubbles"></i> {{{ $post->commentcount }}} @if($post->commentcount == 1)
                        Comment @else Comments @endif
                        </div>
                    <div class="portlet-body bordered">{!! $post->pcontent !!}</div>
                </div>
            </div>
        @endforeach
        <div class="col-md-12">
            {!! $posts->render() !!}
        </div>
    </div>
@endsection

@section('scripts')
<script>
    jQuery(document).ready(function() {
        jQuery('pre').each(function(i, block) {
            hljs.highlightBlock(block);
        });
    });
</script>
@endsection