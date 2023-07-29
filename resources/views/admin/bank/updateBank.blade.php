@extends('layouts.app')

@section('title', 'Create Social Media')


@section('content')
<form action="{{route('bank.update',$id)}}" method="post">
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">token</label>
        <input name="token" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">name</label>
        <input name="name" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">country_id</label>
        <select name="country_id" id="" class="form-select">
            @foreach ($countries as $country)
            <option value="{{$country->id}}">{{$country->name_en}}</option>
            @endforeach
        </select>
    </div>


    <div class="mb-3">
        <label class="form-label">is_active</label>
        <select name="is_active" id="" class="form-select">
            <option value=1>yes</option>
            <option value=0>no</option>
        </select>
    </div>

    <div class="mb-3">
        <input type="submit" class="btn btn-primary w-100">
    </div>
</form>

@endsection
