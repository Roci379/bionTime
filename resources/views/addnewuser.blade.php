@extends('base')

@section('content')
    <div id="app">
        <addnewuser 
        :raw_user=@jsvue($user)
        :names=@jsvue($names)
        :msg=@jsvue($msg)></addnewuser>
    </div>
@endsection
