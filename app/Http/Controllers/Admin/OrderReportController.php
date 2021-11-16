<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderReportController extends Controller
{
    public function index(Request $request){         
        return "test";
         //return view('admin.orderReport');
     }
 
}
