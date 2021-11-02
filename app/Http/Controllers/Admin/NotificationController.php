<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
class NotificationController extends Controller
{
    public function index(){
        $notifications = Notification::orderBy('id','desc')
        ->paginate(10);
        return view('admin.notification.view',compact('notifications'));
    }


    public  function  create(){
        return view('admin.notification.add');
    }


    public  function  store(){
        return view('admin.notification.add');
    }

}
