<?php

namespace App\Http\Livewire\User;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Request;

class UserOrdersDetailsComponent extends Component
{
    public $OrderId;

    public function mount($id){
        $this->OrderId = $id;
    }

    public function cancel(){
        Order::findOrfail($this->OrderId)->update([
            'status'=>'canceled',
            'canceled_date'=>DB::raw('CURRENT_DATE'),
        ]);

        session()->flash('order_message','Order has been canceled!');
    }

    public function render()
    {
        $Order = Order::where('user_id',auth()->user()->id)->findOrFail($this->OrderId);
        return view('livewire.user.user-orders-details-component',compact('Order'))->layout('layouts.base');
    }
}
