@extends('base')

@section('content')
    <div id="app">
        <companycalendar 
        :raw_user=@jsvue($user)
        :events=@jsvue($events)></companycalendar>
    </div>
@endsection

