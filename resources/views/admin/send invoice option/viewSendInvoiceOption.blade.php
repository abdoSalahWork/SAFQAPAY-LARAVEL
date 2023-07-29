@extends('layouts.app')

@section('title', 'send_invoice_options')


@section('content')


<form action="{{route('send_invoice_options')}}" method="post" target="_blank">
    <div class="mb-3">
        <input name="token" placeholder="Token" type="text" class="form-control" value="">
    </div>

    <div class="mb-3">
        <input type="submit" value="Get All Send Invoce" class="btn btn-primary">
    </div>
</form>
<hr>
<div id="routes" class="d-flex col-12">
    <a href="{{route('web.send_invoice_option.add')}}" class="btn btn-primary">Add Send Invoice</a>
</div>
<hr>
<form action="{{route('web.send_invoice_option.edit')}}" method="post">
    @csrf
    <div class="mb-3">
        <input name="id" type="text" placeholder="Enter Id Number" class="form-control">
    </div>
    <div class="mb-3">
        <input type="submit" value="Edit Send Invoce" class="btn btn-warning">
    </div>
</form>
<hr>

<div class="mb-3">
    <input name="id" type="text" id="idItem" placeholder="Enter Id Number" class="form-control mb-3">
    <input value="{{url('api/send_invoice_option/delete')}}" id="url" hidden>
</div>
<input value="Delete Send Invoce" type="button" class="btn btn-danger" onclick="remove()" data-bs-toggle="modal" data-bs-target="#exampleModal">

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="deleteModal">

        </div>
    </div>
</div>

@endsection


