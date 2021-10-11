<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public  function index(Request $request){
        $Ticket=Ticket::orderBy('id','desc')
            ->paginate(10);
        return view('admin.Ticket.view', compact('Ticket'));
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
