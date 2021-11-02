<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileTransfer;
use App\Jobs\SendBulkNotifications;
use Illuminate\Http\Request;
use App\Models\Notification;
class NotificationController extends Controller
{

    use FileTransfer;
//    public function index(){
//        $notifications = Notification::orderBy('id','desc')
//        ->paginate(10);
//        return view('admin.notification.view',compact('notifications'));
//    }


    public  function  create(){
        return view('admin.notification.add');
    }


    public  function  send(Request $request){

        //return $request->all();

        $request->validate([
           'title'=>'required',
           'description'=>'required',
           'type'=>'required|in:partner,customer'
        ]);

        if($request->image)
            $image = $this->getImagePath($request->image, 'notifications');

        dispatch(new SendBulkNotifications($request->title, $request->description, $image??null, $request->type));

        return redirect()->back()->with('success', 'Notifications have been sent');

    }

}
