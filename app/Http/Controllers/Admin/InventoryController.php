<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
class InventoryController extends Controller
{
    public  function index(Request $request){
        $inventory =Inventory::orderBy('id','desc')
            ->paginate(10);
        return view('admin.inventory.view',compact('inventory'));
    }

    public  function create(Request $request){
        return view('admin.inventory.add');
    }

    public function store(Request $request){

    }

    public function edit(Request $request, $id){
        return view('admin.inventory.edit');
    }

    public function update(Request $request, $id){

    }



}
