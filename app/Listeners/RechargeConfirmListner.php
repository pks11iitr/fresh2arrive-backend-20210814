<?php

namespace App\Listeners;

use App\Events\RechargeConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RechargeConfirmListner
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
     * @param  RechargeConfirmed  $event
     * @return void
     */
    public function handle(RechargeConfirmed $event)
    {
        //
    }
}
