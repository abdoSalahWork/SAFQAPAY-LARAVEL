@extends('layouts.app')

@section('title', 'Create Social Media')


@section('content')
<form action="{{route('businessType.store')}}" method="post" enctype="multipart/form-data" target="_blank">

    <div class="mb-3">
        <label class="form-label">token</label>
        <input name="token" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">name_en</label>
        <input name="name_en" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">name_ar</label>
        <input name="name_ar" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">business_logo</label>
        <input name="business_logo" type="file" class="form-control">
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
