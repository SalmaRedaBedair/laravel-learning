<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testController extends Controller
{
    public function sent(Request $request)
    {
        return $request->all();
        if($request->ismethod('post'))
        {
            return $request->all();
        }
        return 'get';
    }
}
