<?php

namespace App\Http\Livewire;

use App\Models\Coupon;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;
use Cart;

class CartComponent extends Component
{
    public $haveCouponCode;
    public $couponCode;
    public $discount;
    public $subTotalAfterDiscount;
    public $taxAfterDiscount;
    public $totalAfterDiscount;

    public function increaseQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId,$qty);
        $this->emitTo('cart-count-component','refreshComponent');
    }
    
    public function decreaseQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId,$qty);
        $this->emitTo('cart-count-component','refreshComponent');
    }
    
    public function destroy($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        session()->flash('success_message','Item has been removed');
        $this->emitTo('cart-count-component','refreshComponent');
    }
    
    public function destroyAll()
    {
        Cart::instance('cart')->destroy();
        $this->emitTo('cart-count-component','refreshComponent');
    }
    
    public function switchToSaveForLater($rowId){
        $item = Cart::instance('cart')->get($rowId);
        Cart::instance('cart')->remove($rowId);
        Cart::instance('SaveForLater')->add($item->id,$item->name,1,$item->price)->associate(Product::class);
        $this->emitTo('cart-count-component','refreshComponent');
        session()->flash('success_message','Item Has been save for later');
    }

    public function MoveToCart($rowId){
        $item = Cart::instance('SaveForLater')->get($rowId);
        Cart::instance('SaveForLater')->remove($rowId);
        Cart::instance('cart')->add($item->id,$item->name,1,$item->price)->associate(Product::class);
        $this->emitTo('cart-count-component','refreshComponent');
        session()->flash('s_success_message','Item Has been move to cart');
    }

    public function deleteFromSaveForLater($rowId){
        Cart::instance('SaveForLater')->remove($rowId);
        session()->flash('s_success_message','Item Has been deleted');
    }

    public function applyCouponCode(){
        $coupon = Coupon::where('code',$this->couponCode)->where('expiry_date','>=',Carbon::today())->where('cart_value','<=',Cart::instance('cart')->subtotal())->first();

        if(!$coupon) {
            if(Coupon::where('code',$this->couponCode)->where('expiry_date','<=',Carbon::today())->first()){
                session()->flash('coupon_message','Promote code is expired');
                return;
            }
            session()->flash('coupon_message','Promote code is invalid');
            return;
        }

        session()->put('coupon',[
            'code'=>$coupon->code,
            'type'=>$coupon->type,
            'value'=>$coupon->value,
            'cart_value'=>$coupon->cart_value,
        ]);
    }

    public function removeCoupon(){
        session()->forget('coupon');
    }
    
    public function calculateDiscount(){
        if(session()->has('coupon')){
            if(session()->get('coupon')['type']=='fixed'){
                $this->discount = session()->get('coupon')['value'];
            } else {
                $this->discount = (Cart::instance('cart')->subtotal() * session()->get('coupon')['value'])/100;
            }
            $this->subTotalAfterDiscount = Cart::instance('cart')->subtotal() - $this->discount;
            $this->taxAfterDiscount = ($this->subTotalAfterDiscount * config('cart.tax'))/100;
            $this->totalAfterDiscount = $this->subTotalAfterDiscount + $this->taxAfterDiscount;
        }
    }

    public function checkout(){
        if(auth()->check()){
            return redirect()->route('checkout');
        } else {
            return redirect()->route('login');
        }
    }

    public function setAmountForCheckout(){
        if(!Cart::instance('cart')->count() > 0){
            session()->forget('checkout');
            return;
        }

        if(session()->has('coupon')){
            session()->put('checkout',[
                'discount'=>$this->discount,
                'subtotal'=>$this->subTotalAfterDiscount,
                'tax'=>$this->taxAfterDiscount,
                'total'=>$this->totalAfterDiscount,
            ]);
        } else {
            session()->put('checkout',[
                'discount'=>0,
                'subtotal'=>Cart::instance('cart')->subtotal(),
                'tax'=>Cart::instance('cart')->tax(),
                'total'=>Cart::instance('cart')->total(),
            ]);
        }
    }

    public function render()
    {
        if(session()->has('coupon')){
            if(Cart::instance('cart')->subtotal() < session()->get('coupon')['cart_value']) {
                session()->forget('coupon');
            } else {
                $this->calculateDiscount();
            }
        }
        $this->setAmountForCheckout();
        return view('livewire.cart-component')->layout('layouts.base');
    }
}
