<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public  function index(Request $request){
        return view('admin.partners.view');
    }

    public  function  create(Request $request){
        return view('admin.partners.add');
    }
}
