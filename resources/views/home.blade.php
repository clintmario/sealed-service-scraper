@extends('layouts.app')

@section('access-links')
    @if($isUserAdmin)
        <li><a href="{{ url('/cms') }}">CMS</a></li>
        <li><a href="{{ url('/admin') }}">Admin</a></li>
    @endif
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
