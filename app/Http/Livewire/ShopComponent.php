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

    public $min_price;
    public $max_price;

    public function mount()
    {
        $this->sorting = 'default';
        $this->pagesize = 10;

        $this->min_price = 0;
        $this->max_price = 10000;
    }
    
    public function store($product_id,$product_name,$product_price)
    {
        Cart::instance('cart')->add($product_id,$product_name,1,$product_price)->associate('App\models\Product');
        session()->flash('success_message','Item added');
        return redirect()->route('product.cart');
    }

    public function addToWishlist($product_id,$product_name,$product_price)
    {
        Cart::instance('wishlist')->add($product_id,$product_name,1,$product_price)->associate('App\models\Product');
        $this->emitTo('wishlist-count-component','refreshComponent');
    }

    public function removeFromWishList($product_id)
    {
        foreach(Cart::instance('wishlist')->content() as $item)
        {
            if($item->id == $product_id)
            {
                Cart::instance('wishlist')->remove($item->rowId);
                $this->emitTo('wishlist-count-component','refreshComponent');
                return;
            }
        }
    }

    public function render()
    {
        if($this->sorting == 'date')
        {
            $product = Product::whereBetween('regular_price',[$this->min_price,$this->max_price])->orderby('created_at','DESC')->paginate($this->pagesize);
        }
        else if($this->sorting == "price")
        {
            $product = Product::whereBetween('regular_price',[$this->min_price,$this->max_price])->orderby('regular_price','asc')->paginate($this->pagesize);
        }
        else if($this->sorting == "price-desc")
        {
            $product = Product::whereBetween('regular_price',[$this->min_price,$this->max_price])->orderby('regular_price','desc')->paginate($this->pagesize);
        }
        else
        {
            $product = Product::whereBetween('regular_price',[$this->min_price,$this->max_price])->paginate($this->pagesize);
        }
        
        $categories = Category::all();

        return view('livewire.shop-component',['products'=>$product , 'categories'=>$categories])->layout('layouts.base');

    }
}
