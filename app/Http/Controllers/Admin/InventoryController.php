<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public  function index(Request $request){
        return view('admin.inventory.view');
    }

    public  function create(Request $request){
        return view('admin.inventory.add');
    }
}
