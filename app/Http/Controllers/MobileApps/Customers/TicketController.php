<?php

namespace App\Http\Controllers\MobileApps\Customers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileTransfer;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketItem;
use Illuminate\Http\Request;
use function Symfony\Component\String\s;

class TicketController extends Controller
{
    use FileTransfer;

    public function raiseTicket(Request $request){

        $user=$request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'action'=>'log_in',
                'display_message'=>'Please log in to continue',
                'data'=>[]
            ];

        $request->validate([
            'type'=>'required|in:item,partner',
            'comments'=>'string',
            'order_id'=>'required|integer',
            'items_quantity'=>'array',
            'items_quantity.*'=>'required|integer',
            'items_issue'=>'array',
            'items_issue.*'=>'required|string',
            'items_image'=>'array',
            'items_image.*'=>'image'
        ]);

        $order = Order::where('user_id', $user->id)
            //->where('status', 'delivered')
            ->find($request->order_id);
        if(!$order || $order->is_completed!=0)
            return [
                'status'=>'failed',
                'action'=>'',
                'display_message'=>'Invalid Request',
                'data'=>[]
            ];

        $max_number = Ticket::max('refid')+1;

        if($request->type=='item'){
            if($order->item_ticket_status!=0)
                return [
                    'status'=>'failed',
                    'action'=>'',
                    'display_message'=>'Invalid Request',
                    'data'=>[]
                ];

            $ticket = Ticket::create([
                'refid'=>$max_number,
                'order_id'=>$request->order_id,
                'customer_comments'=>$request->comments??'',
                'user_id'=>$order->user_id,
                'partner_id'=>$order->delivery_partner,
                'ticket_type'=>'Items Related'
            ]);

            $order->item_ticket_status = 1;
            $order->save();

            $items=[];
            if(isset($request->items_issue)){
                foreach($request->items_issue as $key=>$value){
                    $items[] = new TicketItem([
                        'item_id'=>$key,
                        'packet_count'=>$request->items_quantity[$key]??0,
                        'issue'=>$request->items_issue[$key]??0,
                        'image'=>$this->getImagePath($request->items_image[$key], 'ticket-images/'.$ticket->id)
                    ]);
                }
                if($items){
                    $ticket->items()->saveMany($items);
                }
            }
        }else{

            if($order->partner_ticket_status!=0)
                return [
                    'status'=>'failed',
                    'action'=>'',
                    'display_message'=>'Invalid Request',
                    'data'=>[]
                ];

            Ticket::create([
                'refid'=>$max_number,
                'order_id'=>$request->order_id,
                'customer_comments'=>$request->comments??'',
                'user_id'=>$order->user_id,
                'partner_id'=>$order->delivery_partner,
                'ticket_type'=>'Partner & Delivery Related'
            ]);

            $order->partner_ticket_status = 1;
            $order->save();
        }

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'Ticket has been created',
            'data'=>[]
        ];

    }


    public function ticketDetails(Request $request, $id){
        $user = $request->user;

        $ticket=Ticket::with('items.details')
            ->where('user_id', $user->id)
            ->find($id);

        return [
            'status'=>'success',
            'action'=>'',
            'display_message'=>'',
            'data'=>compact('ticket')
        ];
    }


    public function ticketList(Request $request, $type){
        $user = $request->user;

        $tickets=Ticket::with(['order'=>function($order){
            $order->select('id', 'refid', 'delivery_date', 'created_at');
        }, 'items'=>function($items){
            $items->select('id', 'issue', 'ticket_id');
        }])
            ->where('user_id', $user->id)
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


}
