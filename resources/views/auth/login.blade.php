@extends('layouts.pub.master')

@section('content')
    <div class="container top70">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="portlet light bordered top">
                    <div class="portlet-title">
                        <div class="caption">
                            Login
                        </div>
                    </div>
                    <div class="portlet-body">
                        {!! Former::verticalopen('/login') !!}
                        {!! Former::text('email')->placeholder('Email Address') !!}
                        {!! Former::password('password')->placeholder('Password') !!}
                        {!! Former::submit('login') !!}
                        {!! Former::close() !!}
                    </div>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection