@extends('Fortuna::layout')

@section('head-assets')
    @include('Fortuna.Theme::Base.head-assets')
    <link rel='stylesheet' href='//{{ Config::get('module.app_segment') }}/css/cms.css?ver=1.0' type='text/css' media='all' />
    <script type="text/javascript">
        var gBaseUrl = '//' + '{{ Config::get('module.app_segment') }}';
    </script>
    <script type='text/javascript' src='//{{ Config::get('module.app_segment') }}/js/aws-sdk-2.69.0.min.js?ver=1.0'></script>
    <script type='text/javascript' src='//{{ Config::get('module.app_segment') }}/js/mimeTypes.js?ver=1.0'></script>
    <script type='text/javascript' src='//{{ Config::get('module.app_segment') }}/js/cms.js?ver=1.0'></script>
@stop

@section('head')
    @include('Fortuna::head')
@stop

@section('top-header')
    @include('Fortuna.CMS::Base.top-header')
@stop

@section('header')
    @include('Fortuna.CMS::Base.cms-header')
@stop

@section('sections')
    @foreach ($sections as $section)
        @include("Fortuna.CMS::Section.section-" . $section)
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