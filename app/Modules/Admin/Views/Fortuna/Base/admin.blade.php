@extends('Fortuna::layout')

@section('head-assets')
    @include('Fortuna.Theme::Base.head-assets')
    <link rel='stylesheet' href='//{{ Config::get('module.app_segment') }}/css/admin.css?ver=1.0' type='text/css' media='all' />
    <script type="text/javascript">
        var gBaseUrl = 'http://' + '{{ Config::get('module.app_segment') }}';
    </script>
    <script type='text/javascript' src='//{{ Config::get('module.app_segment') }}/js/admin.js?ver=1.0'></script>
@stop

@section('head')
    @include('Fortuna::head')
@stop

@section('top-header')
    @include('Fortuna.CMS::Base.top-header')
@stop

@section('header')
    @include('Fortuna.Admin::Base.admin-header')
@stop

@section('sections')
    @foreach ($sections as $section)
        @include("Fortuna.Admin::Section.section-" . $section)
    @endforeach
@stop

@section('body-assets')
    @include('Fortuna.Theme::Base.body-assets')
@stop

@section('footer')
    @include('Fortuna::footer')
@stop

@section('body')
    @include('Fortuna::body')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@stop