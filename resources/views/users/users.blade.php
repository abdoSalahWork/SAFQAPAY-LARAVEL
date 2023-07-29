

@extends('layouts.app')
 
@section('title', 'admin managers')
 

@section('content')
<h2 class="mb-2">this page for admin</h2>
<form action="{{route('admin.managers')}}" method="post" target="_blank">
    <div class="mb-3">
        <input type="submit" class="btn btn-primary">
    </div>
</form>
@endsection



