@extends('Fortuna::layout')

@section('head-assets')
    @include('Fortuna.Theme::Base.head-assets')
    <link rel='stylesheet' href='//{{ Config::get('module.app_segment') }}/css/video-js.css?ver=1.0' type='text/css' media='all' />
    <link rel='stylesheet' href='//{{ Config::get('module.app_segment') }}/css/application.css?ver=1.0' type='text/css' media='all' />
    <script type="text/javascript">
        var gBaseUrl = '//' + '{{ Config::get('module.app_segment') }}';
    </script>
    <script type='text/javascript' src='//{{ Config::get('module.app_segment') }}/js/video.js?ver=1.0'></script>
    <script type='text/javascript' src='//{{ Config::get('module.app_segment') }}/js/application.js?ver=1.0'></script>
@stop

@section('head')
    @include('Fortuna::head')
@stop

@section('top-header')
    @include('Fortuna.Application::Base.landing-top-header')
@stop

@section('header')
    @include('Fortuna.Application::Base.application-header')
@stop

@section('sections')
    @foreach ($sections as $section)
        @include("Fortuna.Application::Section.section-" . $section)
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

        $(document).ready(function () {
            var player = videojs('cm-video');
            var lessonTracked = false;
            player.on('play', function () {
                if (lessonTracked == false) {
                    $.ajax({
                        type: "POST",
                        url: '/watch_lesson',
                        data: 'lesson_id=' + $('#lesson-id').val(),
                        success: function (data) {
                            if (data.status == 1) {
                                lessonTracked = true;
                            }
                        },
                        dataType: 'json'
                    });
                }
            });
        });
    </script>
@stop