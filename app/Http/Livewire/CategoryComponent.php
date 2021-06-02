<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
USE App\Models\Category;
use Cart;

class CategoryComponent extends Component
{
    use WithPagination;
    public $sorting;
    public $pagesize;
    public $category_slug;

    public function mount($category_slug)
    {
        $this->sorting = 'default';
        $this->pagesize = 10;
        $this->category_slug = $category_slug;
    }
    
    public function store($product_id,$product_name,$product_price)
    {
        Cart::add($product_id,$product_name,1,$product_price)->associate('App\models\Product');
        session()->flash('success_message','Item added');
        return redirect()->route('product.cart');
    }

    public function render()
    {
        $category = Category::where('slug',$this->category_slug)->first();
        $category_id = $category->id;
        $category_name = $category->name;


        if($this->sorting == 'date')
        {
            $product = Product::where('category_id',$category_id)->orderby('created_at','DESC')->paginate($this->pagesize);
        }
        else if($this->sorting == "price")
        {
            $product = Product::where('category_id',$category_id)->orderby('regular_price','asc')->paginate($this->pagesize);
        }
        else if($this->sorting == "price-desc")
        {
            $product = Product::where('category_id',$category_id)->orderby('regular_price','desc')->paginate($this->pagesize);
        }
        else
        {
            $product = Product::where('category_id',$category_id)->paginate($this->pagesize);
        }
        
        $categories = Category::all();

        return view('livewire.category-component',['products'=>$product , 'categories'=>$categories , 'category_name'=>$category_name])->layout('layouts.base');

    }
}
