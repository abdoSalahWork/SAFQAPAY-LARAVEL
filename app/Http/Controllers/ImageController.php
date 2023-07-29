<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    public function avatarUser($id, $name)
    {
        // if is admin
        // if id == auth id
        return response()->file("../storage/app/public/images/users/$id/$name");
    }

    function logoBsusinessType($name)
    {
        return response()->file("../storage/app/public/images/admin/business_type/$name");
    }

    function imageProduct($id, $name)
    {

        return response()->file("../storage/app/public/images/product/$id/$name");
    }
    function logoProfile($id, $name)
    {

        return response()->file("../storage/app/public/images/profile/$id/$name");
    }

    function flagCountry($name)
    {

        return response()->file("../storage/app/public/images/admin/country/$name");
    }

    function fileInvice($id, $name)
    {

        return response()->file("../storage/app/public/files/invoice/$id/$name");
    }
    function logoPaymentMethod($name)
    {

        return response()->file("../storage/app/public/images/paymentMethod/logo/$name");
    }
    function imageMessage($name)
    {
        return response()->file("../storage/app/public/images/message/$name");
    }
    function imageDocuments($name)
    {
        return response()->file("../storage/app/public/images/documents/$name");
    }
    function iconSocialMedia($name)
    {
        return response()->file("../storage/app/public/images/socialMedia/$name");
    }
    function logoAboutStore($name)
    {
        return response()->file("../storage/app/public/images/aboutStore/$name");
    }
    
}
