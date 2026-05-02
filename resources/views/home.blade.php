@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content_header')
    <h1>{{ __('Dashboard') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>0</h3>
                    <p>{{ __('New Orders') }}</p>
                </div>
                <div class="icon"><i class="fas fa-shopping-bag"></i></div>
                <a href="#" class="small-box-footer">{{ __('More info') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>0</h3>
                    <p>{{ __('Users') }}</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="#" class="small-box-footer">{{ __('More info') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>0</h3>
                    <p>{{ __('Tasks') }}</p>
                </div>
                <div class="icon"><i class="fas fa-tasks"></i></div>
                <a href="#" class="small-box-footer">{{ __('More info') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>0</h3>
                    <p>{{ __('Notifications') }}</p>
                </div>
                <div class="icon"><i class="fas fa-bell"></i></div>
                <a href="#" class="small-box-footer">{{ __('More info') }} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
@stop
