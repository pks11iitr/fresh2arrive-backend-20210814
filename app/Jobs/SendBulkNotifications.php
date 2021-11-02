<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Partner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class SendBulkNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $title, $description, $image, $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title, $description, $image, $type)
    {
        $this->description=$description;
        $this->title=$title;
        $this->type=$type;
        $this->image=$image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch($this->type){
            case 'partner': return $this->sendPartnerNotifications();
            case 'customer': return $this->sendCustomerNotifications();
        }
    }

    public function sendPartnerNotifications(){
        $partners = Partner::select('notification_token')
            ->where('notification_token', '!=', null)
            ->get();

        $messaging = app('firebase.messaging');


        if(!empty($this->image)){
            $notification = Notification::fromArray([
                'title' => $this->title,
                'body' => $this->description,
                'image' => Storage::url($this->image),
            ]);
        }else{
            $notification = Notification::fromArray([
                'title' => $this->title,
                'body' => $this->description
            ]);
        }

        foreach($partners as $partner){

            try {
                $message = CloudMessage::withTarget('token', $partner->notification_token)
                    ->withNotification($notification);
                $messaging->send($message);
            }catch (\Exception $e){

            }
        }
    }


    public function sendCustomerNotifications(){

        $customers = Customer::select('notification_token')
            ->where('notification_token', '!=', null)
            ->get();

        $messaging = app('firebase.messaging');


        if(!empty($this->image)){
            $notification = Notification::fromArray([
                'title' => $this->title,
                'body' => $this->description,
                'image' => Storage::url($this->image),
            ]);
        }else{
            $notification = Notification::fromArray([
                'title' => $this->title,
                'body' => $this->description
            ]);
        }

        foreach($customers as $customer){

            try {
                $message = CloudMessage::withTarget('token', $customer->notification_token)
                    ->withNotification($notification);
                $messaging->send($message);
            }catch (\Exception $e){

            }




        }

    }

}
