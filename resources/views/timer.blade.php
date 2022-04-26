@extends('base')

@section('content')
    <div id="app">
        <timer
        :raw_user=@jsvue($user)
        :records=@jsvue($view_records)
        :hours=@jsvue($hours)
        :time_stages=@jsvue($time_stages)
        :daily_records=@jsvue($daily_records)
        :laststate=@jsvue($laststate)></timer>
    </div>
@endsection

