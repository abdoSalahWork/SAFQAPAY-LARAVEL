@extends('layouts.app')

@section('title', 'Create Customer')


@section('content')
<form action="{{route('customer.update',$id)}}" method="post" target="_blank">
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">token</label>
        <input name="token" type="text" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">full_name</label>
        <input name="full_name" type="text" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label class="form-label">phone_number</label>
        <input name="phone_number" type="text" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label class="form-label">phone_number_code_id</label>
        <select name="phone_number_code_id" class="form-select">
            @foreach ($countries as $country)
            <option value="{{$country['id']}}" {{$country['default']?'selected':''}}>{{$country['code']}}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">email</label>
        <input name="email" type="email" class="form-control" value="">
    </div>


    <div class="mb-3">
        <label class="form-label">customer_reference</label>
        <input name="customer_reference" type="text" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label class="form-label">Bank ID</label>
        <select name="bank_id" class="form-select">
        <option value selected>--None--</option>
            @foreach ($banks as $bank)
            <option value="{{$bank['id']}}" >{{$bank['name']}}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">bank_account</label>
        <input name="bank_account" type="text" class="form-control" value="">
    </div>

    <div class="mb-3">
        <label class="form-label">iban</label>
        <input name="iban" value="123" type="text" class="form-control" value="">
    </div>

    <div class="mb-3">
        <input type="submit" class="btn btn-primary w-100">
    </div>
</form>

@endsection
