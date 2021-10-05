<?php

namespace App\Http\Controllers\MobileApps\Partners;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function ticketList(Request $request, $type){
        $user = $request->user;

        $tickets=Ticket::with([
            'customer'=>function($customer){
                $customer->select('id', 'name', 'mobile', 'image');
            },
            'order'=>function($order){
                $order->select('id', 'refid');
            },
            'items'=>function($details){
                $details->select('id', 'issue', 'ticket_id', 'detail_id', 'packet_count');
            }])
            ->where('partner_id', $user->id)
            ->where('status', $type)
            ->orderBy('id', 'desc')
            ->paginate(10);

        foreach($tickets as $t){
            $t->issue = implode(',', $t->items->map(function($element){
                return $element->issue;
            })->toArray());
        }

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('tickets')
        ];
    }


    public function details(Request $request, $id){
        $user = $request->user;

        $ticket=Ticket::with(['items.details', 'order'=>function($order){
            $order->select('id', 'created_at', 'delivery_date', 'status', 'order_total')->withCount('details');
        }])
            ->where('partner_id', $user->id)
            ->find($id);

        foreach($ticket->items as $t){
            $t->ticket_raising_for = 'Issue Raised For '.$t->packet_count;
        }

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('ticket')
        ];
    }


    public function update(Request $request){

        $user = $request->user;

        $request->validate([
            'ticket_id'=>'required'
        ]);

        $ticket=Ticket::with('items.details')
            ->where('partner_id', $user->id)
            ->find($request->ticket_id);

        $ticket->partner_approved = !empty($request->is_approved)?1:0;
        $ticket->partner_comments = $request->comments;
        $ticket->partner_responded = true;

        $ticket->save();

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Ticket has been updated',
            'data'=>[]
        ];


    }
}
