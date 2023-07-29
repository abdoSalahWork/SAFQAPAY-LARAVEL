@extends('layouts.app')

@section('title', 'Create Social Media')


@section('content')
<form action="{{route('country.update',$country->id)}}" method="post" enctype="multipart/form-data">
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">token</label>
        <input name="token" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">name_en</label>
        <input name="name_en" type="text" value="{{$country->name_en}}" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">name_ar</label>
        <input name="name_ar" type="text" value="{{$country->name_ar}}" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">code</label>
        <input name="code" type="text" value="{{$country->code}}" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">nationality</label>
        <input name="nationality" value="{{$country->nationality}}" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">currency</label>
        <input name="currency" value="{{$country->currency}}" type="text" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">short_currency</label>
        <input name="short_currency" value="{{$country->short_currency}}" type="text" class="form-control">
    </div>
    <div class="mb-3">
        <img src="{{$country->flag}}" width="45px" alt="">
        <label class="form-label">flag</label>
        <input name="flag" type="file" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">default</label>
        <select name="default" id="" class="form-select">
                <option value=1>yes</option>
                <option value=0 selected>no</option>
        </select>
    </div>
    <div class="mb-3">
        <input type="submit" class="btn btn-primary w-100">
    </div>
</form>

@endsection
