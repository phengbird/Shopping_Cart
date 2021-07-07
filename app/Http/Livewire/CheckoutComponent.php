<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;
use App\Models\Transaction;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Stripe;
use Livewire\Component;

class CheckoutComponent extends Component
{
    public $ship_to_different;

    public $firstname;
    public $lastname;
    public $line1;
    public $line2;
    public $email;
    public $mobile;
    public $province;
    public $city;
    public $country;
    public $zipcode;

    public $s_firstname;
    public $s_lastname;
    public $s_line1;
    public $s_line2;
    public $s_email;
    public $s_mobile;
    public $s_province;
    public $s_city;
    public $s_country;
    public $s_zipcode;

    public $payment_method;
    public $thankyou;

    public $card_no;
    public $expiry_month;
    public $expiry_year;
    public $cvc;

    public function updated($fields){
        $this->validateOnly($fields,[
            'firstname'=>'required',
            'lastname'=>'required',
            'line1'=>'required',
            'email'=>'required|email',
            'mobile'=>'required|numeric',
            'province'=>'required',
            'city'=>'required',
            'country'=>'required',
            'zipcode'=>'required',
            'payment_method'=>'required'
        ]);

        if($this->ship_to_different)
        {
            $this->validateOnly($fields,[
                's_firstname'=>'required',
                's_lastname'=>'required',
                's_line1'=>'required',
                's_email'=>'required|email',
                's_mobile'=>'required|numeric',
                's_province'=>'required',
                's_city'=>'required',
                's_country'=>'required',
                's_zipcode'=>'required',
            ]);
        }

        if($this->payment_method == 'card') {
            $this->validateOnly($fields, [
                'card_no'=>'required',
                'expiry_month'=>'required|numeric',
                'expiry_year'=>'required|numeric',
                'cvc'=>'required|numeric',
            ]);
        }
    }

    public function placeOrder(){
        $this->validate([
            'firstname'=>'required',
            'lastname'=>'required',
            'line1'=>'required',
            'email'=>'required|email',
            'mobile'=>'required|numeric',
            'province'=>'required',
            'city'=>'required',
            'country'=>'required',
            'zipcode'=>'required',
            'payment_method'=>'required'
        ]);

        if($this->payment_method == 'card') {
            $this->validate([
                'card_no'=>'required',
                'expiry_month'=>'required|numeric',
                'expiry_year'=>'required|numeric',
                'cvc'=>'required|numeric',
            ]);
        }

        $order = Order::create([
            'user_id'=>auth()->user()->id,
            'subtotal'=>session()->get('checkout')['subtotal'],
            'discount'=>session()->get('checkout')['discount'],
            'tax'=>session()->get('checkout')['tax'],
            'total'=>session()->get('checkout')['total'],
            'is_shipping_different'=>$this->ship_to_different ? 1 : 0,
            'firstname'=>$this->firstname,
            'lastname'=>$this->lastname,
            'line1'=>$this->line1,
            'line2'=>$this->line2,
            'email'=>$this->email,
            'mobile'=>$this->mobile,
            'province'=>$this->province,
            'country'=>$this->country,
            'city'=>$this->city,
            'zipcode'=>$this->zipcode,
        ]);

        foreach(Cart::instance('cart')->content() as $item){
            OrderItem::create([
                'order_id'=>$order->id,
                'product_id'=>$item->id,
                'price'=>$item->total,
                'quantity'=>$item->qty,
            ]);
        }

        if($this->ship_to_different)
        {
            $this->validate([
                's_firstname'=>'required',
                's_lastname'=>'required',
                's_line1'=>'required',
                's_email'=>'required|email',
                's_mobile'=>'required|numeric',
                's_province'=>'required',
                's_city'=>'required',
                's_country'=>'required',
                's_zipcode'=>'required',
            ]);

            Shipping::create([
                'order_id'=>$order->id,
                'firstname'=>$this->s_firstname,
                'lastname'=>$this->s_lastname,
                'line1'=>$this->s_line1,
                'line2'=>$this->s_line2,
                'email'=>$this->s_email,
                'mobile'=>$this->s_mobile,
                'province'=>$this->s_province,
                'country'=>$this->s_country,
                'city'=>$this->s_city,
                'zipcode'=>$this->s_zipcode,
            ]);
        }

        if($this->payment_method == 'cod'){
            $this->makeTransaction($order->id,'pending');
            $this->resetCart();      
        } else if($this->payment_method == 'card') {
            $stripe = Stripe::make(env('STRIPE_KEY'));
            
            try{
                $token = $stripe->tokens()->create([
                    'card' => [
                        'number'=>$this->card_no,
                        'exp_month'=>$this->expiry_month,
                        'exp_year'=>$this->expiry_year,
                        'cvc'=>$this->cvc,
                    ]
                ]);
                
                if(!isset($token['id'])) {
                    session()->flash('stripe_error','The Stripe token was not generate correctly!');
                    $this->thankyou = 0;
                }

                $customer = $stripe->customers()->create([
                    'name'=>$this->firstname.' '.$this->lastname,
                    'email'=>$this->email,
                    'phone'=>$this->mobile,
                    'address'=>[
                        'line1'=>$this->line1,
                        'postal_code'=>$this->zipcode,
                        'city'=>$this->city,
                        'state'=>$this->province,
                        'country'=>$this->country
                    ],
                    'shipping'=>[
                        'name'=>$this->firstname.' '.$this->lastname,
                        'address'=>[
                            'line1'=>$this->line1,
                            'postal_code'=>$this->zipcode,
                            'city'=>$this->city,
                            'state'=>$this->province,
                            'country'=>$this->country
                        ],
                    ],
                    'source'=>$token['id']
                ]);

                $charge = $stripe->charges()->create([
                    'customer'=>$customer['id'],
                    'currency'=>'USD',
                    'amount'=>session()->get('checkout')['total'],
                    'description'=>'Payment for order no'.$order->id,
                ]);

                if($charge['status'] == 'succeeded') {
                    $this->makeTransaction($order->id,'approved');
                    $this->resetCart();
                } else {
                    session()->flash('stripe_error','Error in Transaction');
                    $this->thankyou = 0;
                }
            } catch(Exception $e) {
                session()->flash('stripe_error',$e->getMessage());
                $this->thankyou = 0;
            }
        }
        
    }

    public function resetCart(){
        $this->thankyou = 1;
        Cart::instance('cart')->destroy();
        session()->forget('checkout');
    }

    public function makeTransaction($order_id,$status){
        Transaction::create([
            'user_id'=>auth()->user()->id,
            'order_id'=>$order_id,
            'mode'=>$this->payment_method,
            'status'=>$status,
        ]);
    }

    public function verifyForCheckout(){
        if(!auth()->check()){
            return redirect()->route('login');
        } else if($this->thankyou) {
            return redirect()->route('thankyou');
        } else if(!session()->get('checkout')) {
            return redirect()->route('product.cart');
        }
    }

    public function render()
    {
        $this->verifyForCheckout();
        return view('livewire.checkout-component')->layout('layouts.base');
    }
}
