<?php 


namespace App\Exports;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerExport implements FromView
{
    protected $customers;

    function __construct($customers) { 
        $this->customers = $customers;
      }
       

    public function view(): View
    {  
        return view('admin.customers.export', [
            'customer' => $this->customers             
        ]);        
    }
}

 