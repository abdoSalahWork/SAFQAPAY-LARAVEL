@extends('layouts.app')

@section('title', 'social_media')


@section('content')
<form action="{{route('social_media')}}" method="post" target="_blank">
    <div class="mb-3">
        <input name="token" placeholder="Token" type="text" class="form-control" value="">
    </div>

    <div class="mb-3">
        <input type="submit" value="Get All Social Media" class="btn btn-primary">
    </div>
</form>
<hr>
<div id="routes">
    <a href="{{route('web.social_media.add')}}" class="btn btn-success">Add Social Media</a>
</div>
<hr>
<form action="{{route('web.social_media.edite')}}" method="post" target="_blank">
    @csrf
    <div class="mb-3">
        <input name="id" type="text" placeholder="Enter Id Number" class="form-control">
    </div>
    <div class="mb-3">
        <input type="submit" value="Edit Social Media" class="btn btn-warning">
    </div>
</form>

<hr>
<div class="mb-3">
    <input name="id" type="text" id="idItem" placeholder="Enter Id Number" class="form-control mb-3">
    <input value="{{url('api/social_media/delete')}}" id="url" hidden>

</div>
<input value="Delete Social Media" type="button" class="btn btn-danger" onclick="remove()" data-bs-toggle="modal" data-bs-target="#exampleModal">

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="deleteModal">

        </div>
    </div>
</div>


@endsection
