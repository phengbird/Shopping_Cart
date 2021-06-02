<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
USE App\Models\Category;
use Cart;

class SearchComponent extends Component
{
    use WithPagination;
    public $sorting;
    public $pagesize;

    public $search;
    public $product_cat;
    public $product_cat_id;

    public function mount()
    {
        $this->sorting = 'default';
        $this->pagesize = 10;
        $this->fill(request()->only('search','product_cat','product_cat_id'));
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
            $product = Product::where('name','like','%'.$this->search.'%')->where('category_id','like','%'.$this->product_cat_id.'%')->orderby('created_at','DESC')->paginate($this->pagesize);
        }
        else if($this->sorting == "price")
        {
            $product = Product::where('name','like','%'.$this->search.'%')->where('category_id','like','%'.$this->product_cat_id.'%')->orderby('regular_price','asc')->paginate($this->pagesize);
        }
        else if($this->sorting == "price-desc")
        {
            $product = Product::where('name','like','%'.$this->search.'%')->where('category_id','like','%'.$this->product_cat_id.'%')->orderby('regular_price','desc')->paginate($this->pagesize);
        }
        else
        {
            $product = Product::where('name','like','%'.$this->search.'%')->where('category_id','like','%'.$this->product_cat_id.'%')->paginate($this->pagesize);
        }
        
        $categories = Category::all();

        return view('livewire.search-component',['products'=>$product , 'categories'=>$categories])->layout('layouts.base');

    }
}
