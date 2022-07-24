<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class salesReportExport implements FromView
{

    protected $orders;
   

    function __construct($quantities) {
        $this->quantities = $quantities;
      }

    public function view(): View
    {
        return view('admin.orders.sale-excel-report', [
            'quantities' => $this->quantities
            //'partners' => $this->partners
        ]);
    }

}

