@extends('base')

@section('content')
    <div id="app">
        <management 
        :raw_user=@jsvue($user)
        :users=@jsvue($users)
        :names=@jsvue($names)></management>
    </div>
@endsection

