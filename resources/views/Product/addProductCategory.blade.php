@extends('layouts.app')

@section('title', 'Create Customer')


@section('content')
<form action="{{route('product.category.store')}}" method="post" target="_blank">

    <div class="mb-3">
        <label class="form-label">token</label>
        <input name="token" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">name_en</label>
        <input name="name_en" type="text" class="form-control" value="test">
    </div>

    <div class="mb-3">
        <label class="form-label">name_ar</label>
        <input name="name_ar" type="text" class="form-control" value="تجربة">
    </div>

    <div class="mb-3">
        <label class="form-label">is_active</label>
        <select class="form-select" name="is_active" id="">
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select>
    </div>
    <div class="mb-3">
        <input type="submit" class="btn btn-primary w-100">
    </div>
</form>

@endsection
