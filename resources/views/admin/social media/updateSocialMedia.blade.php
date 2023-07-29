@extends('layouts.app')

@section('title', 'Create Social Media')


@section('content')
<form action="{{route('social_media.update',$id)}}" method="post">
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">token</label>
        <input name="token" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">name_en</label>
        <input name="name_en" type="text" class="form-control" >
    </div>

    <div class="mb-3">
        <label class="form-label">name_ar</label>
        <input name="name_ar" type="text" class="form-control" >
    </div>

    <div class="mb-3">
        <label class="form-label">icon</label>
        <input name="icon" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <input type="submit" class="btn btn-primary w-100">
    </div>
</form>

@endsection
