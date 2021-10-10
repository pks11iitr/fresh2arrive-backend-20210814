<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileTransfer;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Models\Category;


class CategoryController extends Controller
{
    use FileTransfer;

    public  function  index(Request  $request){
        $category = Category :: active()->orderBy('id','desc')
            ->paginate(10);
        return view('admin.category.view',compact('category'));
    }

    public  function  create(Request $request){
        return view('admin.category.add');
    }

    public function store(Request $request){

        $request->validate([
            'name'=>'required',
            'image'=>'required',
            'earn_upto'=>'required',
            'isactive'=>'required'
        ]);

        $category = Category::create(array_merge(
            $request->only('name', 'isactive', 'earn_upto'),
            [
                'image'=>$this->getImagePath($request->image, 'categories')
            ]
        ));

        return redirect()->route('category.edit', $category->id)
            ->with('message','Category Addedd Successfully');
    }

    public function edit(Request $request, $id){
        $category =Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id){

    }

}
