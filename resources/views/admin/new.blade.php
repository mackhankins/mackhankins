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
        {!! Former::verticalopen(action('Admin\IndexController@store'))->enctype('multipart/form-data') !!}
        <div class="col-md-9">
            <div class="portlet light bordered top">
                <div class="portlet-title">
                    <div class="caption">
                        Post
                    </div>
                </div>
                <div class="portlet-body">
                    {!! Former::text('title') !!}
                    {!! Former::textarea('excerpt') !!}
                    {!! Former::textarea('content') !!}
                    {!! Former::hidden('user_id')->value($user->id) !!}
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
                    {!! Former::select('status')->options(['draft' => 'draft', 'published' => 'published'])->label('') !!}
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
    <script src="{{ elixir('js/redactor.js') }}"></script>
    <script type="text/javascript">
        jQuery(function () {
            jQuery('#content').redactor({
                maxHeight: 1000,
                focus: true,
                imageUpload: '{!! action("Admin\IndexController@upload") !!}',
                imageManagerJson: '{!! $filesjson !!}',
                plugins: ['table', 'imagemanager']
            });
        });
    </script>
@endsection