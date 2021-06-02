<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
USE App\Models\Category;
use Cart;

class ShopComponent extends Component
{
    use WithPagination;
    public $sorting;
    public $pagesize;

    public function mount()
    {
        $this->sorting = 'default';
        $this->pagesize = 10;
    }
    
    public function store($product_id,$product_name,$product_price)
    {
        Cart::add($product_id,$product_name,1,$product_price)->associate('App\models\Product');
        session()->flash('success_message','Item added');
        return redirect()->route('product.cart');
    }

    public function render()
    {
        if($this->sorting == 'date')
        {
            $product = Product::orderby('created_at','DESC')->paginate($this->pagesize);
        }
        else if($this->sorting == "price")
        {
            $product = Product::orderby('regular_price','asc')->paginate($this->pagesize);
        }
        else if($this->sorting == "price-desc")
        {
            $product = Product::orderby('regular_price','desc')->paginate($this->pagesize);
        }
        else
        {
            $product = Product::paginate($this->pagesize);
        }
        
        $categories = Category::all();

        return view('livewire.shop-component',['products'=>$product , 'categories'=>$categories])->layout('layouts.base');

    }
}
