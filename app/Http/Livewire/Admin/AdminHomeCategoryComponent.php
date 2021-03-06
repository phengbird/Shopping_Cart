<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\HomeCategory;
use Livewire\Component;

class AdminHomeCategoryComponent extends Component
{
    public $select_categories = [];
    public $numberofproducts;

    public function mount()
    {
        $category = HomeCategory::find(1);
        $this->select_categories = explode(',',$category->sel_categories);
        $this->numberofproducts = $category->No_of_products;
    }

    public function updateHomeCategory()
    {
        $category = HomeCategory::first();
        $category->update([
            'sel_categories'=>implode(',',$this->select_categories),
            'No_of_products'=>$this->numberofproducts,
        ]);
        // $category->sel_categories = implode(',',$this->select_categories);
        // $category->No_of_products = $this->numberofproducts;
        // $category->save();
        session()->flash('message','Category has been updated');
    }

    public function render()
    {
        $categories = Category::all();
        return view('livewire.admin.admin-home-category-component',['categories'=>$categories])->layout('layouts.base');
    }
}
