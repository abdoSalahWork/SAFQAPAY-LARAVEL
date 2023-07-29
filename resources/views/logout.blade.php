

@extends('layouts.app')
 
@section('title', 'logout')
 

@section('content')
<form action="{{route('logout')}}" method="post" target="_blank">
    <div class="mb-3">
        <label class="form-label">Token</label>
        <input name="token" type="text"  class="form-control" value="">
    </div>

    <div class="mb-3">
        <input type="submit" class="btn btn-primary">
    </div>
</form>
@endsection



