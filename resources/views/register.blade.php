



@extends('layouts.app')
 
@section('title', 'register')
 

@section('content')
<form action="{{route('register')}}" method="post" target="_blank">
    <div class="mb-3">
        <label class="form-label">countryId</label>
        <select  name="country_id" class="form-select" >
            @foreach ($country as $item)
                <option value="{{$item['id']}}"  {{$item['default']?'selected':''}}>{{$item['name']}}</option>
            @endforeach
        </select>
    </div>
    
    <div class="mb-3">
        <label class="form-label">phoneNumberCode</label>
        <select name="phone_number_code_id" class="form-select" >
            @foreach ($country as $item)
                <option value="{{$item['id']}}"  {{$item['default']?'selected':''}}>{{$item['code']}}</option>
            @endforeach
        </select>
    </div>
    
    <div class="mb-3">
        <label class="form-label">phoneNumber</label>
        <input type="text" value="01060929469" name="phone_number" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">businessTypeId</label>
        <select name="business_type_id" class="form-select" >
            @foreach ($businessType as $item)
                <option value="{{$item['id']}}"  {{$item['default']?'selected':''}}>{{$item['name']}}</option>
            @endforeach
        </select>
    </div>
    
    <div class="mb-3">
        <label class="form-label">categoryId</label>
        <select name="category_id" class="form-select" >
            @foreach ($category as $item)
                <option value="{{$item['id']}}"  {{$item['default']?'selected':''}}>{{$item['name']}}</option>
            @endforeach
        </select>
    </div>
    
    <div class="mb-3">
        <label class="form-label">languageId</label>
        <select name="language_id" class="form-select" >
            @foreach ($language as $item)
                <option value="{{$item['id']}}"  {{$item['default']?'selected':''}}>{{$item['name']}}</option>
            @endforeach
        </select>
    </div>
    
    <div class="mb-3">
        <label class="form-label">companyName</label>
        <input type="text" value="company test" name="company_name" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">nameEN</label>
        <input type="text" value="name en" name="name_en" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">nameAR</label>
        <input type="text" value="name ar" name="name_ar" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">workEmail</label>
        <input type="email" value="info@site.com" name="work_email" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">bankAccountName</label>
        <input type="text" value="1234" name="bank_account_ame" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">bankName</label>
        <input type="text" value="test" name="bank_name" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">AccountNumber</label>
        <input type="text" value="123" name="account_number" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">Iban</label>
        <input type="text" value="123" name="iban" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">email manager</label>
        <input type="email" value="info@site2.com" name="email" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">fullName manager</label>
        <input type="text" value="full manager" name="full_name" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">phoneNumberCodeManager</label>
        <select name="phone_number_code_manager_id" class="form-select">
            @foreach ($country as $item)
                <option value="{{$item['id']}}"  {{$item['default']?'selected':''}}>{{$item['code']}}</option>
            @endforeach
        </select>
    </div>
    
    <div class="mb-3">
        <label class="form-label">phoneNumberManager</label>
        <input type="text" value="01060929469" name="phone_number_manager" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">nationality manager</label>
        <select name="nationality_id" class="form-select">
            @foreach ($country as $item)
                <option value="{{$item['id']}}"  {{$item['default']?'selected':''}}>{{$item['nationality']}}</option>
            @endforeach
        </select>
    </div>
    
    <div class="mb-3">
        <label class="form-label">password manager</label>
        <input type="password" value="123456789" name="password" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label">password_confirmation</label>
        <input type="password" value="123456789" name="password_confirmation" class="form-control">
    </div>
    
    <div class="mb-3">
        <input type="submit" class="btn btn-primary">
    </div>
</form>

@endsection



