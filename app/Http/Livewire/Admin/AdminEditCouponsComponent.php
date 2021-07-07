<?php

namespace App\Http\Livewire\Admin;

use App\Models\Coupon;
use Livewire\Component;

class AdminEditCouponsComponent extends Component
{
    public $Coupon;

    public $code;
    public $type;
    public $value;
    public $cart_value;
    public $expiry_date;

    public function mount(Coupon $coupon,$coupon_id){
        $this->Coupon = $coupon->findOrFail($coupon_id);
        $this->code = $this->Coupon->code;
        $this->type = $this->Coupon->type;
        $this->value = $this->Coupon->value;
        $this->cart_value = $this->Coupon->cart_value;
        $this->expiry_date = $this->Coupon->expiry_date;
    }

    public function updated($fields){
      $this->validateOnly($fields,[
        'code'=>'required',
        'type'=>'required',
        'value'=>'required|numeric',
        'cart_value'=>'required|numeric',
        'expiry_date'=>'required'
      ]);
    }

    public function updateCoupon(){
         $this->validate([
            'code'=>'required',
            'type'=>'required',
            'value'=>'required|numeric',
            'cart_value'=>'required|numeric',
            'expiry_date'=>'required'
        ]);
        
        $this->Coupon->update([
            'code' => $this->code,
            'type' => $this->type,
            'value'=>$this->value,
            'cart_value'=>$this->cart_value,
            'expiry_date'=>$this->expiry_date
        ]);

        session()->flash('message','Coupon has been updated successfully!');
        redirect()->route('admin.coupons');
    }

    public function render()
    {
        return view('livewire.admin.admin-edit-coupons-component')->layout('layouts.base');
    }
}
