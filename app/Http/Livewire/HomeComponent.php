<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\HomeCategory;
use App\Models\HomeSlider;
use App\Models\Product;
use App\Models\Sale;
use Livewire\Component;

class HomeComponent extends Component
{
    public function render()
    {
        $slider = HomeSlider::where('status','1')->get();
        $latest_product = Product::orderBy('created_at','desc')->get()->take(8);
        $category = HomeCategory::find(1);
        $cats = explode(',',$category->sel_categories);
        $categories = Category::whereIn('id',$cats)->get();
        $No_of_products = $category->No_of_products;
        $sale_products = Product::where('sale_price','>','0')->inRandomOrder()->get()->take(8);
        $sale_date = Sale::get()->first();
        return view('livewire.home-component',['sliders'=>$slider , 'latest_product'=>$latest_product , 'categories'=>$categories , 'No_of_products'=>$No_of_products , 'sale_products'=>$sale_products , 'sale_date'=>$sale_date])->layout('layouts.base');
    }
}
