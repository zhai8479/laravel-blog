<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function login(Request $request)
    {
        $request->user_name;
        $request->password;
        $request->input('password');
    }
}
