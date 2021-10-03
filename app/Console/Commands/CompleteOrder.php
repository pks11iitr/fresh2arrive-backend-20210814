<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CompleteOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = date('Y-m-d H:i:s', strtotime('-24 hours'));

        Order::where('status', 'delivered')
            ->where('is_completed', false)
            ->where('delivered_at', '<', $date)
            ->update(['is_completed'=>true]);
    }
}
