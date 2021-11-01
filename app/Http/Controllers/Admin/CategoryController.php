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
        $categories = Category :: active()->orderBy('id','desc')
            ->paginate(10);
        return view('admin.category.view',compact('categories'));
    }

    public  function  create(Request $request){
        return view('admin.category.add');
    }

    public function store(Request $request){

        $request->validate([
            'name'=>'required',
            'image'=>'required',
            'earn_upto'=>'required',
            'isactive'=>'required',
            'font_color'=>'required'
        ]);

        $category = Category::create(array_merge(
            $request->only('name', 'isactive', 'earn_upto', 'font_color'),
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
        $request->validate([
            'name'=>'required',
            //'image'=>'required',
            'earn_upto'=>'required',
            'isactive'=>'required',
            'font_color'=>'required'
        ]);
        $category=Category::findOrFail($id);

        if($request->image){
            $path = $this->getImagePath($request->image, 'banners');
        }else{
            $path = $category->getRawOriginal('image');
        }

        $category->update([
            'name'=>$request->name,
            'isactive'=>$request->isactive,
            'image'=>$path,
            'earn_upto'=>$request->earn_upto,
            'font_color'=>$request->font_color

        ]);

        return redirect()->back()->with('success', 'Category has been updated');

    }

}
