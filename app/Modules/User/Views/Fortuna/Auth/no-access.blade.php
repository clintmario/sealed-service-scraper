@extends('Fortuna::layout')

@section('head-assets')
    @include('Fortuna.Theme::Base.head-assets')
@stop

@section('head')
    @include('Fortuna::head')
@stop

@section('top-header')
    @include('Fortuna::top-header')
@stop

@section('header')
    @include('Fortuna::header')
@stop

@section('sections')
    @foreach ($sections as $section)
        @include("Fortuna.User::Auth.section-" . $section)
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
@stop