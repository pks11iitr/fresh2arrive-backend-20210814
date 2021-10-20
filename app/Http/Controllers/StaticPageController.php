<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPageController extends Controller
{

    public function about(){
        return view('about-us');
    }

    public function tnc(){
        return view('tnc');
    }


    public function privacy(){
        return view('privacy-policy');
    }
}
