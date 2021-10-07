<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public  function index(Request $request){
        return view('admin.customers.view');
    }

    public  function create(Request $request){
        return view('admin.customers.add');
    }
}
