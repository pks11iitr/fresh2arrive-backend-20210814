<?php 


namespace App\Exports;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerExport implements FromView
{
    public function view(): View
    {
        return view('admin.customers.export', [
            'customer' =>  Customer::withCount(['orders'=>function($orders){
                $orders->where('orders.status', '!=', 'cancelled');
            }])->get()
        ]);
    }
}