<?php

namespace App\Http\Livewire\Admin;

use App\Models\Coupon;
use Livewire\Component;

class AdminAddCouponsComponent extends Component
{
    public $Coupon;

    public $code;
    public $type;
    public $value;
    public $cart_value;
    public $expiry_date;

    public function mount(Coupon $coupon){
        $this->Coupon = $coupon;
    }

    public function updated($fields){
      $this->validateOnly($fields,[
        'code'=>'required|unique:coupons',
        'type'=>'required',
        'value'=>'required|numeric',
        'cart_value'=>'required|numeric',
        'expiry_date'=>'required'
      ]);
    }

    public function storeCoupon(){

         $this->validate([
            'code'=>'required|unique:coupons',
            'type'=>'required',
            'value'=>'required|numeric',
            'cart_value'=>'required|numeric',
            'expiry_date'=>'required'
        ]);
        
        $this->Coupon->create([
            'code' => $this->code,
            'type' => $this->type,
            'value'=>$this->value,
            'cart_value'=>$this->cart_value,
            'expiry_date'=>$this->expiry_date
        ]);

        session()->flash('message','Coupon has been created successfully!');
        redirect()->route('admin.coupons');
    }

    public function render()
    {
        return view('livewire.admin.admin-add-coupons-component')->layout('layouts.base');
    }
}
