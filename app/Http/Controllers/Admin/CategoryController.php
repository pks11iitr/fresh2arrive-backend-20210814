<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;


class CategoryController extends Controller
{
    public  function  index(Request  $request){
        $category = Category :: active()->orderBy('id','desc')
            ->paginate(10);
        return view('admin.category.view',compact('category'));
    }

    public  function  create(Request $request){
        return view('admin.category.add');
    }



    public function store(Request $request){

    }

    public function edit(Request $request, $id){
        return view('admin.category.edit');
    }

    public function update(Request $request, $id){

    }

}
