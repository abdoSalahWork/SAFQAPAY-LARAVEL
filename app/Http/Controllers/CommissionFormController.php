<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CommissionFormController extends Controller
{
    public function index(){
       $commission_forms = DB::table('commission_from')->get();
       return response()->json(['data' => $commission_forms]);
    }
}
