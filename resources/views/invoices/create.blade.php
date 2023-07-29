@extends('layouts.app')

@section('title', 'Create Invoice')


@section('content')
<form action="{{route('invoice.store')}}" method="post" target="_blank" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">token</label>
        <input name="token" type="text" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">customer_name</label>
        <input name="customer_name" type="text" class="form-control" value="mohamed samir">
    </div>
    <div class="mb-3">
        <label class="form-label">send_invoice option</label>
        <select name="send_invoice_option_id" class="form-select">
            @foreach ($send_invoice_option as $item)
            <option value="{{$item['id']}}" {{$item['default']?'selected':''}}>{{$item['name']}}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">customer_mobile</label>
        <input name="customer_mobile" type="text" class="form-control" value="01060929469">
    </div>

    <div class="mb-3">
        <label class="form-label">customer_mobile_code</label>
        <select name="customer_mobile_code_id" class="form-select">
            @foreach ($country as $item)
            <option value="{{$item['id']}}" {{$item['default']?'selected':''}}>{{$item['code']}}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">customer_email</label>
        <input name="customer_email" type="email" class="form-control" value="email@site.com">
    </div>


    <div class="mb-3">
        <label class="form-label">customer_reference</label>
        <input name="customer_reference" type="text" class="form-control" value="1234">
    </div>

    <div class="mb-3">
        <label class="form-label">currency_id</label>
        <select name="currency_id" class="form-select">
            @foreach ($country as $item)
            <option value="{{$item['id']}}" {{$item['default']?'selected':''}}>{{$item['currency']}}</option>
            @endforeach
        </select>
    </div>


    <div class="mb-3">
        <label class="form-label">language</label>
        <select name="language_id" class="form-select">
            @foreach ($language as $item)
            <option value="{{$item['id']}}" {{$item['default']?'selected':''}}>{{$item['name']}}</option>
            @endforeach
        </select>
    </div>


    <div class="mb-3">
        <label class="form-label">Recurring Interval</label>
        <select name="recurring_interval_id" class="form-select">
            @foreach ($recurring_nterval as $item)
            <option value="{{$item['id']}}" {{$item['default']?'selected':''}}>{{$item['name']}}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">is_open_invoice</label>
        <select name="is_open_invoice" class="form-select">
            <option value="0">Changeable</option>
            <option value="1">Fixed</option>
        </select>
    </div>


    <div class="mb-3">
        <label class="form-label">discount_type</label>
        <select name="discount_type" class="form-select">
            <option value="0">Fixed Rate</option>
            <option value="1">Rate %</option>
        </select>
    </div>


    <div class="mb-3">
        <label class="form-label">discount_value</label>
        <input name="discount_value" type="number" class="form-control" value="0">
    </div>

    <div class="mb-3">
        <label class="form-label">recurring_start_date</label>
        <input name="recurring_start_date" type="date" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">recurring_end_date</label>
        <input name="recurring_end_date" type="date" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">expiry_date</label>
        <input name="expiry_date" type="text" value="2022-10-08 10:40" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">attach_file</label>
        <input name="attach_file" type="file" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">comment</label>
        <textarea name="comment" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">terms_and_conditions</label>
        <textarea name="terms_and_conditions" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">remind_after</label>
        <input name="remind_after" type="number" class="form-control" value="0">
    </div>

    <div id="co-invoice-item">
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label class="form-label">product_name</label>
                    <input name="product_name[]" type="text" class="form-control" required>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label class="form-label">product_quantity</label>
                    <input name="product_quantity[]" type="number" class="form-control" value="0">
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label class="form-label">product_price</label>
                    <input name="product_price[]" type="number" class="form-control" value="0">
                </div>
            </div>
            <div class="col"></div>
        </div>
    </div>


    <div class="row my-3">
        <div class="col"></div>
        <div class="col"></div>
        <div class="col"></div>
        <div class="col">
            <button onclick="createNewInvoiceItem()" type="button" class="btn btn-primary w-100"> create New</button>
        </div>
    </div>





    <div class="mb-3">
        <input type="submit" class="btn btn-primary w-100">
    </div>
</form>

<script>
    function createNewInvoiceItem() {
        document.querySelector('#co-invoice-item').innerHTML += `
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label class="form-label">product_name</label>
                    <input name="product_name[]" type="text"  class="form-control" required>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label class="form-label">product_quantity</label>
                    <input name="product_quantity[]" type="number"  class="form-control" value="0">
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label class="form-label">product_price</label>
                    <input name="product_price[]" type="number"  class="form-control" value="0">
                </div>
            </div>
            <div class="col">
                <button onclick="deleteInvoiceItem(this)" type="button" class="btn btn-danger"> delete</button>
            </div>
        </div>
        `;
    }

    function deleteInvoiceItem(x) {
        x.parentNode.parentElement.innerHTML = '';

    }
</script>
@endsection
