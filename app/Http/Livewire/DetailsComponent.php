<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Sale;
use Livewire\Component;
use Cart;

class DetailsComponent extends Component
{
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function store($product_id,$product_name,$product_price)
    {
        Cart::add($product_id,$product_name,1,$product_price)->associate('App\models\Product');
        session()->flash('success_message','Item added');
        return redirect()->route('product.cart');
    }

    public function render()
    {
        $product = Product::where('slug',$this->slug)->first();
        $popular_product = Product::inRandomOrder()->limit(4)->get();
        $relate_product = Product::where('category_id',$product->category_id)->inRandomOrder()->limit(5)->get();
        $sale_date = Sale::get()->first();
        return view('livewire.details-component',['product' => $product , 'popular_product' => $popular_product , 'relate_product' => $relate_product , 'sale_date'=>$sale_date])->layout('layouts.base');
    }
}
