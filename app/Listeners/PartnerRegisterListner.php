<?php

namespace App\Listeners;

use App\Events\PartnerRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PartnerRegisterListner
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
     * @param  PartnerRegistered  $event
     * @return void
     */
    public function handle(PartnerRegistered $event)
    {
        //
    }
}
