@extends('layouts.app')
 
@section('title', 'update user')
 

@section('content')
{{-- {{route('update.manager.user')}} --}}
<form action="{{route('admin.update.manager',$user->id)}}" method="post" target="_blank"  enctype="multipart/form-data">
    <div class="mb-3">
        <input disabled type="text" value="{{$user->id}}"  name="id" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">email manager</label>
        <input disabled value="{{$user->email}}" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">fullName manager</label>
        <input type="text" value="{{$user->full_name}}" name="full_name" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">avatar url</label>
        <img src="{{$user->avatarUrl}}" style="width: 200px" alt="">
        <input type="file" name="avatar" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">phoneNumberCodeManager</label>
        <select name="phone_number_code_manager_id" class="form-select">
            @foreach ($country as $item)
                <option value="{{$item['id']}}"  {{($item['id'] == $user->phone_number_code_manager_id)?'selected':''}}>{{$item['code']}}</option>
            @endforeach
        </select>
    </div>
    
    <div class="mb-3">
        <label class="form-label">phoneNumberManager</label>
        <input type="text" value="{{$user->phone_number_manager}}" name="phone_number_manager" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">nationality manager</label>
        <select name="nationality_id" class="form-select">
            @foreach ($country as $item)
                <option value="{{$item['id']}}"  {{($item['id'] == $user->nationality_id)?'selected':''}}>{{$item['nationality']}}</option>
            @endforeach
        </select>
    </div>




    <div class="form-check">
        <input name="enable_bell_sound" class="form-check-input" type="checkbox" {{($user->enable_bell_sound)?'checked':''}}>
        <label class="form-check-label">
            enable bell sound
        </label>
    </div>
    <div class="form-check">
        <input name="confirm_email" class="form-check-input" type="checkbox" {{($user->confirm_email)?'checked':''}}>
        <label class="form-check-label">
            confirm email
        </label>
    </div>
    <div class="form-check">
        <input name="confirm_phone" class="form-check-input" type="checkbox" {{($user->confirm_phone)?'checked':''}}>
        <label class="form-check-label">
            confirm phone
        </label>
    </div>
    <div class="form-check">
        <input name="batch_invoices" class="form-check-input" type="checkbox" {{($user->batch_invoices)?'checked':''}}>
        <label class="form-check-label">
            batch invoices
        </label>
    </div>
    <div class="form-check">
        <input name="deposits" class="form-check-input" type="checkbox" {{($user->deposits)?'checked':''}}>
        <label class="form-check-label">
            deposits
        </label>
    </div>
    <div class="form-check">
        <input name="payment_links" class="form-check-input" type="checkbox" {{($user->payment_links)?'checked':''}}>
        <label class="form-check-label">
            payment_links
        </label>
    </div>
    <div class="form-check">
        <input name="profile" class="form-check-input" type="checkbox" {{($user->profile)?'checked':''}}>
        <label class="form-check-label">
            profile
        </label>
    </div>
    <div class="form-check">
        <input name="users" class="form-check-input" type="checkbox" {{($user->users)?'checked':''}}>
        <label class="form-check-label">
            users
        </label>
    </div>
    

    <div class="form-check">
        <input name="refund" class="form-check-input" type="checkbox" {{($user->refund)?'checked':''}}>
        <label class="form-check-label">
            refund
        </label>
    </div>
    <div class="form-check">
        <input name="show_all_invoices" class="form-check-input" type="checkbox" {{($user->show_all_invoices)?'checked':''}}>
        <label class="form-check-label">
            show_all_invoices
        </label>
    </div>
    <div class="form-check">
        <input name="customers" class="form-check-input" type="checkbox" {{($user->customers)?'checked':''}}>
        <label class="form-check-label">
            customers
        </label>
    </div>
    <div class="form-check">
        <input name="invoices" class="form-check-input" type="checkbox" {{($user->invoices)?'checked':''}}>
        <label class="form-check-label">
            invoices
        </label>
    </div>
    <div class="form-check">
        <input name="products" class="form-check-input" type="checkbox" {{($user->products)?'checked':''}}>
        <label class="form-check-label">
            products
        </label>
    </div>
    <div class="form-check">
        <input name="commissions" class="form-check-input" type="checkbox" {{($user->commissions)?'checked':''}}>
        <label class="form-check-label">
            commissions
        </label>
    </div>
    <div class="form-check">
        <input name="account_statements" class="form-check-input" type="checkbox" {{($user->account_statements)?'checked':''}}>
        <label class="form-check-label">
            account_statements
        </label>
    </div>
    <div class="form-check">
        <input name="orders" class="form-check-input" type="checkbox" {{($user->orders)?'checked':''}}>
        <label class="form-check-label">
            orders
        </label>
    </div>
    <div class="form-check">
        <input name="suppliers" class="form-check-input" type="checkbox" {{($user->suppliers)?'checked':''}}>
        <label class="form-check-label">
            suppliers
        </label>
    </div>
    <div class="form-check">
        <input name="notification_create_invoice" class="form-check-input" type="checkbox" {{($user->notification_create_invoice)?'checked':''}}>
        <label class="form-check-label">
            notification_create_invoice
        </label>
    </div>
    <div class="form-check">
        <input name="notification_invoice_paid" class="form-check-input" type="checkbox" {{($user->notification_invoice_paid)?'checked':''}}>
        <label class="form-check-label">
            notification_invoice_paid
        </label>
    </div>
    <div class="form-check">
        <input name="notification_new_order" class="form-check-input" type="checkbox" {{($user->notification_new_order)?'checked':''}}>
        <label class="form-check-label">
            notification_new_order
        </label>
    </div>
    <div class="form-check">
        <input name="notification_create_batch_invoice" class="form-check-input" type="checkbox" {{($user->notification_create_batch_invoice)?'checked':''}}>
        <label class="form-check-label">
            notification_create_batch_invoice
        </label>
    </div>
    <div class="form-check">
        <input name="notification_deposit" class="form-check-input" type="checkbox" {{($user->notification_deposit)?'checked':''}}>
        <label class="form-check-label">
            notification_deposit
        </label>
    </div>
    <div class="form-check">
        <input name="notification_create_recurring_invoice" class="form-check-input" type="checkbox" {{($user->notification_create_recurring_invoice)?'checked':''}}>
        <label class="form-check-label">
            notification_create_recurring_invoice
        </label>
    </div>
    <div class="form-check">
        <input name="notification_refund_transfered" class="form-check-input" type="checkbox" {{($user->notification_refund_transfered)?'checked':''}}>
        <label class="form-check-label">
            notification_refund_transfered
        </label>
    </div>
    <div class="form-check">
        <input name="notification_notifications_service_request" class="form-check-input" type="checkbox" {{($user->notification_notifications_service_request)?'checked':''}}>
        <label class="form-check-label">
            notification_notifications_service_request
        </label>
    </div>
    <div class="form-check">
        <input name="notification_notifications_hourly_deposit_rejected" class="form-check-input" type="checkbox" {{($user->notification_notifications_hourly_deposit_rejected)?'checked':''}}>
        <label class="form-check-label">
            notification_notifications_hourly_deposit_rejected
        </label>
    </div>
    <div class="form-check">
        <input name="notification_approve_vendor_account" class="form-check-input" type="checkbox" {{($user->notification_approve_vendor_account)?'checked':''}}>
        <label class="form-check-label">
            notification_approve_vendor_account
        </label>
    </div>
    <div class="form-check">
        <input name="notification_create_shipping_invoice" class="form-check-input" type="checkbox" {{($user->notification_create_shipping_invoice)?'checked':''}}>
        <label class="form-check-label">
            notification_create_shipping_invoice
        </label>
    </div>
    <hr>
    <div class="form-check">
        <input name="is_enable" class="form-check-input" type="checkbox" {{($user->is_enable)?'checked':''}}>
        <label class="form-check-label">
            is_enable
        </label>
    </div>
    <div class="form-check">
        <input name="is_enable" class="form-check-input" type="checkbox" {{($user->is_enable)?'checked':''}}>
        <label class="form-check-label">
            is_enable
        </label>
    </div>
    <hr>
    <div class="mb-3">
        <label class="form-label">new password manager</label>
        <input type="password"  name="password" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">new password confirmation</label>
        <input type="password"  name="password_confirmation" class="form-control">
    </div>
    <hr>
    <div class="mb-3">
        <input type="submit" class="btn btn-primary">
    </div>
</form>

@endsection