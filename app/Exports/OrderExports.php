<?php
namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class OrderExports implements FromView
{

    protected $orderdata;

    function __construct($orderdata) {
        $this->orderdata = $orderdata;
      }

    public function view(): View
    {
        return view('exports.invoices', [
            'orderdata' => $this->orderdata
        ]);
    }
}