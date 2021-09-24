<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request){

        $user = $request->user;

        $name = $user->name;

        $banners=Banner::active()
            ->select('id','image', 'type')
            ->orderBy('sequence_no', 'asc')
            ->get();




        return compact('banners', 'name');


    }
}
