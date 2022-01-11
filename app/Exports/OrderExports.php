<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class OrderExports implements FromView
{

    protected $quantities;
    
    function __construct($quantities) {
        
        $this->quantities = $quantities;

      }

    public function view(): View
    {
        return view('admin.orders.order-excel-report', [
            'quantities' => $this->quantities
        ]);
    }

}

