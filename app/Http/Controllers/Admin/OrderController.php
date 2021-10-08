<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){
        return view('admin.orders.view');
    }

    public  function create(Request $request){
        return view('admin.orders.add');
    }
}
