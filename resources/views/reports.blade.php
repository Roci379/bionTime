@extends('base')

@section('content')
    <div id="app">
        <reports 
        :raw_user=@jsvue($user)
        :info=@jsvue($info)>
        </reports>
    </div>
@endsection

