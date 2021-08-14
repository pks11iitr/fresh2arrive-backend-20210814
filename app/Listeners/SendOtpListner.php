<?php

namespace App\Listeners;

use App\Events\SendOtp;
use App\Services\SMS\Msg91;
use App\Services\SMS\Nimbusit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOtpListner
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendOtp  $event
     * @return void
     */
    public function handle(SendOtp $event)
    {
        $dlt_id=$this->get_dlt_id($event->type);
        Msg91::send($event->mobile,$event->message, $dlt_id);
    }


    private function get_dlt_id($type=null){
        return env('OTP_DLT_ID');
    }
}
