@extends('base')

@section('content')
    <div id="app">
        <dashboard  
        :raw_user=@jsvue($user)
        :hours=@jsvue($hours)
        :percentages=@jsvue($percentages)
        :laststate=@jsvue($laststate)
        :events=@jsvue($events)></dashboard>
    </div>
@endsection


