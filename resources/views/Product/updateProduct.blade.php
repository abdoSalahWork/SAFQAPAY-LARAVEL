@extends('layouts.app')

@section('title', 'Products')


@section('content')
<div class="modal-body">
    <form action="{{route('product.update',$product->id)}}" enctype="multipart/form-data" class="d-flex flex-wrap" method="post" target="_blank">
        @method('PUT')
        <div class="col-6 mb-1 px-1">
            <label class="form-label">category_id</label>
            <select class="form-select" name="category_id" id="">
                <option value="{{$product->category->id}}" selected>{{$product->category->name_en}}</option>
                @foreach ($productCategories as $category)
                @if ($category->id != $product->category->id)
                <option value="{{$category->id}}">{{$category->name_en}}</option>
                @endif
                @endforeach
            </select>
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">name_en</label>
            <input name="name_en" type="text" class="form-control" value="{{$product->name_en}}">
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">name_ar</label>
            <input name="name_ar" type="text" class="form-control" value="{{$product->name_ar}}">
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">description_en</label>
            <input name="description_en" type="text" class="form-control" value="{{$product->description_en}}">
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">description_ar</label>
            <input name="description_ar" type="text" class="form-control" value="{{$product->description_ar}}">
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">quantity</label>
            <input name="quantity" type="number" class="form-control" value="{{$product->quantity}}">
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">price</label>
            <input name="price" type="number" class="form-control" value="{{$product->price}}">
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">is_stockable</label>
            <select class="form-select" name="is_stockable" id="">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">disable_product_on_sold</label>
            <select class="form-select" name="disable_product_on_sold" id="">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">is_active</label>
            <select class="form-select" name="is_active" id="">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <div class="col-6 mb-1 px-1">
            <img src="{{$product->product_image}}" width="45" alt="">
            <label class="form-label">product_image</label>
            <input name="product_image" type="file" class="form-control">
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">is_shipping_product</label>
            <select class="form-select" name="is_shipping_product" id="">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">weight</label>
            <input name="weight" type="number" class="form-control" value="{{$product->weight}}">
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">height</label>
            <input name="height" type="number" class="form-control" value="{{$product->height}}">
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">width</label>
            <input name="width" type="number" class="form-control" value="{{$product->width}}">
        </div>

        <div class="col-6 mb-1 px-1">
            <label class="form-label">length</label>
            <input name="length" type="number" class="form-control" value="{{$product->length}}">
        </div>

        <div class="col-12 mb-2 px-1">
            <label class="form-label">token</label>
            <input name="token" type="text" class="form-control">
        </div>

        <div class="mb-3">
            <input type="submit" class="btn btn-primary w-100">
        </div>

    </form>
</div>
@endsection
