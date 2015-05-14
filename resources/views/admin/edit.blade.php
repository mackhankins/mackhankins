@extends('layouts.pub.master')

@section('page-header')
    <div class="top70"></div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{!! URL::asset('css/redactor.css') !!}">
    <link rel="stylesheet" href="{!! URL::asset('css/github.css') !!}">
@endsection

@section('content')
    <div class="row">
        {!! Former::verticalopen(action('Admin\IndexController@update', ['id' => $post->id]))->enctype('multipart/form-data') !!}
        <div class="col-md-9">
            <div class="portlet light bordered top">
                <div class="portlet-title">
                    <div class="caption">
                        Post
                    </div>
                </div>
                <div class="portlet-body">
                    {!! Former::text('title')->value($post->title) !!}
                    {!! Former::text('slug')->value($post->slug) !!}
                    {!! Former::textarea('excerpt')->value($post->excerpt) !!}
                    {!! Former::textarea('content')->value($post->pcontent)->rows('30') !!}
                    {!! Former::hidden('user_id')->value($post->user->id) !!}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="portlet light bordered top">
                <div class="portlet-title">
                    <div class="caption">
                        Featured Image
                    </div>
                </div>
                <div class="portlet-body">
                    <img src="{{ URL::to('images/medium/'.$post->featuredimage.$post->mimetype) }}"
                         alt="{{$post->title}}" class="post-image"/>

                    <div class="top10">
                        {!! Former::file('featured')->label('')->accept('image') !!}
                    </div>
                </div>
            </div>
            <div class="portlet light bordered top">
                <div class="portlet-title">
                    <div class="caption">
                        Post Status
                    </div>
                </div>
                <div class="portlet-body">
                    {!! Former::select('status')->options(['draft' => 'draft', 'published' => 'published'], $post->status)->label('') !!}
                    {!! Former::actions()
                    ->large_primary_submit('Submit')
                    ->large_inverse_reset('Reset') !!}
                </div>
            </div>
        </div>
        {!! Former::close() !!}
    </div>
@endsection

@section('scripts')

@endsection