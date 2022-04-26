@extends('base')

@section('content')
    <div id="app">
        <mycalendar 
        :raw_user=@jsvue($user)
        :events=@jsvue($events)
        :others=@jsvue($others)
        :disponibility=@jsvue($disponibility)></mycalendar>
    </div>
@endsection

