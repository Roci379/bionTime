@extends('base')

@section('content')
    <div id="app">
    <myprofile
        :raw_user=@jsvue($user)
        :functions=@jsvue($function)
    ></myprofile>
    </div>
@endsection

