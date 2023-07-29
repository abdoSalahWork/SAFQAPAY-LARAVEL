<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(){
        $roles = UserRole::get();
        return response()->json(['data' => $roles]);
    }
}
