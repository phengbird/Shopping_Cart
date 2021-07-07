<?php

namespace App\Http\Livewire\Admin;

use App\Models\Coupon;
use Livewire\Component;

class AdminCouponsComponent extends Component
{
    public $Coupon;
    
    public function mount(Coupon $coupon)
    {
        $this->Coupon = $coupon;
    }

    public function deleteCoupon($id){
        $this->Coupon->findOrFail($id)->delete();
        session()->flash('success_message','Coupon has been deleted');
    }
    
    public function render()
    {
        $Coupons = $this->Coupon->all();
        return view('livewire.admin.admin-coupons-component',['Coupons'=>$Coupons])->layout('layouts.base');
    }
}
