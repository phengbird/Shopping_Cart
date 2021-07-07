<?php

namespace App\Http\Livewire\Admin;

use App\Models\Sale;
use Livewire\Component;

class AdminSaleComponent extends Component
{
    public $sale_date;
    public $status;
    public $sale_id;

    public function mount()
    {
        $sale = Sale::first();
        $this->sale_date = $sale->sale_date;
        $this->status = $sale->status;
        $this->sale_id = $sale->id;
    }

    public function updateSale()
    {
        $sale= Sale::find($this->sale_id);
        $sale->sale_date = $this->sale_date;
        $sale->status = $this->status;
        $sale->save();
        session()->flash('message','Record has been updated');
    }

    public function render()
    {
        return view('livewire.admin.admin-sale-component')->layout('layouts.base');
    }
}
