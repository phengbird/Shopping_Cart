<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Request;

class AdminOrderDetailsComponent extends Component
{
    public function render(Request $request)
    {
        $Order = Order::findOrFail($request->id);
        return view('livewire.admin.admin-order-details-component',compact('Order'))->layout('layouts.base');
    }
}
