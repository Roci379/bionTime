@extends('base')

@section('content')
    <div id="app">
        <timerdaily :raw_user="{{$user}}"></timerdaily>
    </div>
@endsection

