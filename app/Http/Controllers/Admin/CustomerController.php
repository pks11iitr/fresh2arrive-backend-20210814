<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
class CustomerController extends Controller
{
    public  function index(Request $request){

        $customer = Customer::orderBy('id','desc')
            ->paginate(10);
        return view('admin.customers.view',compact('customer'));

    }

    public  function create(Request $request){
        return view('admin.customers.add');
    }


    public function store(Request $request){

    }

    public function edit(Request $request, $id){
        return view('admin.customers.edit');
    }

    public function update(Request $request, $id){

    }

}
