@extends('base')

@section('content')
    <div id="app">
        <modifyuser 
        :raw_user=@jsvue($user)
        :names=@jsvue($names)></modifyuser>
    </div>
@endsection

