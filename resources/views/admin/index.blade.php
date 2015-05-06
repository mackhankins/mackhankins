@extends('layouts.pub.master')

@section('page-header')
    <div class="top70"></div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered top">
                <div class="portlet-title">
                    <div class="caption">
                        Blog Posts
                    </div>
                </div>
                <div class="portlet-body">
                    <table width="100%" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <td>Title</td>
                            <td>Status</td>
                            <td>Created</td>
                            <td>Updated</td>
                            <td>Actions</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>{{ $post->title }}</td>
                                <td>{{ $post->status }}</td>
                                <td>
                                    {!!
                                    \Carbon\Carbon::createFromTimeStamp(strtotime($post->created_at))->toFormattedDateString()
                                    !!}
                                </td>
                                <td>
                                    {!!
                                    \Carbon\Carbon::createFromTimeStamp(strtotime($post->updated_at))->toFormattedDateString()
                                    !!}
                                </td>
                                <td>
                                    <a href="{!! action('Admin\IndexController@edit', $post->id) !!}">Edit</a>
                                    |
                                    <a href="{!! action('Admin\IndexController@delete', $post->id) !!}">Delete</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            {!! $posts->render() !!}
        </div>
    </div>
@endsection