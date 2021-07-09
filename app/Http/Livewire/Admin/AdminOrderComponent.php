<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminOrderComponent extends Component
{
    
    public function updateOrderStatus($order_id,$status){
        $Order = Order::findOrFail($order_id);
        $Order->status = $status;
        
        if($status == 'delivered')
            $Order->update(['delivered_date'=>DB::raw('CURRENT_DATE')]);
        else if($status == 'canceled')
            $Order->update(['canceled_date'=>DB::raw('CURRENT_DATE')]);

        session()->flash('order_message','Order status has benn updated successfull!');
        
        redirect()->route('admin.orders');
    }

    public function render()
    {
        $Orders = Order::orderBy('created_at','DESC')->paginate(12);
        return view('livewire.admin.admin-order-component',compact('Orders'))->layout('layouts.base');
    }
}
