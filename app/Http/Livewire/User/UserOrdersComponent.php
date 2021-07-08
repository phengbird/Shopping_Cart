<?php

namespace App\Http\Livewire\User;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UserOrdersComponent extends Component
{
    public function render()
    {
        $Orders = Order::where('user_id',auth()->user()->id)->paginate(12);
        return view('livewire.user.user-orders-component',compact('Orders'))->layout('layouts.base');
    }
}
