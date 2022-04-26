@extends('base')

@section('content')
    <div id="app">
        <timerweekly :raw_user="{{$user}}"></timerweekly>
    </div>
@endsection

