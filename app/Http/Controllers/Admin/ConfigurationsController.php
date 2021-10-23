<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationsController extends Controller
{
    public  function index(Request $request){
            $config=Configuration::orderBy('id','desc')
                ->paginate(10);
        return view('admin.Configurations.view', compact('config',));
    }



    public  function edit(Request $request,$id){
        $config=Configuration::findOrFail($id);
        return view('admin.Configurations.edit', compact('config'));
    }



    public function update(Request $request,$id){
        $request->validate([
            'value'=>'required'
        ]);
        $config=Configuration::findOrFail($id);
        $config->update([
            'value'=>$request->value
        ]);
        return redirect()->back()->with('success', 'Configurations has been updated');
    }
}
