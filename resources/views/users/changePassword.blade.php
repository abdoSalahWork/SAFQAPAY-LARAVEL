

@extends('layouts.app')
 
@section('title', 'Change Passeord')
 

@section('content')

<form action="{{route('change.password')}}" method="post" target="_blank">
    <div class="mb-3">
        <label class="form-label">Token</label>
        <input name="token" type="text"  class="form-control" value="">
    </div>

    <div class="mb-3">
        <label class="form-label">Old Password</label>
        <input name="old_password" type="password"  class="form-control" value="">
    </div>
    <div class="mb-3">
        <label class="form-label">New Password</label>
        <input name="new_password" type="password"  class="form-control" value="">
    </div>
    <div class="mb-3">
        <label class="form-label">confirmation Password</label>
        <input name="new_password_confirmation" type="password"  class="form-control" value="">
    </div>

    <div class="mb-3">
        <input type="submit" class="btn btn-primary">
    </div>
</form>
@endsection



