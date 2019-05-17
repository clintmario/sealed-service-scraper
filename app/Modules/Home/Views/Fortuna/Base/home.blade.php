@extends('Fortuna::layout')

@section('head-assets')
    @include('Fortuna::home-head-assets')
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
        @include("Fortuna.Home::Section.section-" . $section)
    @endforeach
@stop

@section('body-assets')
    @include('Fortuna::home-body-assets')
@stop

@section('footer')
    @include('Fortuna::footer')
@stop

@section('body')
    @include('Fortuna::body')
@stop
