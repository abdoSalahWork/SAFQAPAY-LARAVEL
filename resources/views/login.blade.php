@extends('layouts.app')

@section('title', 'login')


@section('content')
<form action="{{route('login')}}" method="post" target="_blank">
    <div class="mb-3">
        <label class="form-label">email manager</label>
        <input name="email" type="email"  class="form-control" value="info@my-website-1.com">
    </div>

    <div class="mb-3">
        <label class="form-label">password manager</label>
        <input name="password" type="password" class="form-control" value="123456789">
    </div>

    <div class="mb-3">
        <input type="submit" class="btn btn-primary">
    </div>
</form>
@endsection



