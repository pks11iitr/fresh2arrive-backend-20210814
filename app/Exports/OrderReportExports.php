<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class OrderReportExports implements FromView
{

    protected $orders;
    protected $partners;

    function __construct($orders, $partners) {

        $this->orders = $orders;
        $this->partners = $partners;

      }

    public function view(): View
    {
        return view('admin.orders.order-full-excel-report', [
            'orders' => $this->orders,
            'partners' => $this->partners
        ]);
    }

}

