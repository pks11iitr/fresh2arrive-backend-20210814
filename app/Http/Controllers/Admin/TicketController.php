<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Partner;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public  function index(Request $request){

        if($request->search){
            $Tickets=Ticket::where('refid','LIKE',"%$request->search%")
                   ->paginate(10);
        }else{
            $Tickets=Ticket::orderBy('id','desc')
                ->paginate(10);
        }
        $partner = Partner::get();
        return view('admin.Ticket.view', compact('Tickets','partner'));
    }



    public  function edit(Request $request,$id){
          $Ticket=Ticket::findOrFail($id); 
        return view('admin.Ticket.edit', compact('Ticket'));
    }



    public function update(Request $request,$id){
        $request->validate([
            'status'=>'required',
            'admin_comments'=>'required'
        ]);
        $Ticket=Ticket::findOrFail($id);
        $Ticket->update([
            'status'=>$request->status,
            'admin_comments'=>$request->admin_comments
        ]);
        return redirect()->back()->with('success', 'Ticket has been updated');
    }
}
