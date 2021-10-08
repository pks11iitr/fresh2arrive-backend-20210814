<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request){
        $products = Product::active()->orderBy('id','desc')
            ->paginate(10);
        return view('admin.products.view',compact('products'));
    }

    public  function create(Request $request){
        return view('admin.products.add');
    }


    public function store(Request $request){

    }

    public function edit(Request $request, $id){
        return view('admin.products.edit');
    }

    public function update(Request $request, $id){

    }

}
