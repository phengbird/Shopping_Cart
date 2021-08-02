<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Component;
use Carbon\Carbon;
use Livewire\WithFileUploads;

class AdminEditProductComponent extends Component
{
    use WithFileUploads;
    public $name;
    public $slug;
    public $short_description;
    public $description;
    public $regular_price;
    public $sale_price;
    public $sku;
    public $stock_status;
    public $featured;
    public $quantity;
    public $image;
    public $category_id;
    public $newimage;
    public $product_id;

    public $images;
    public $newimages;

    public function mount($product_slug)
    {
        $product = Product::where('slug',$product_slug)->first();
        $this->name = $product->name; 
        $this->slug = $product->slug; 
        $this->short_description = $product->short_description ;
        $this->description = $product->description ;
        $this->regular_price = $product->regular_price ;
        $this->sale_price = $product->sale_price ;
        $this->sku = $product->SKU ;
        $this->stock_status = $product->stock_status ;
        $this->featured = $product->featured ;
        $this->quantity = $product->quantity ;
        $this->image = $product->image ;
        $this->category_id = $product->category_id ;
        $this->newimage = $product->newimage ;
        $this->product_id = $product->id ;
        $this->images = explode(",",$product->images);
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name,'-');
    }

    public function updated($feilds)
    {
        $this->validateOnly($feilds,[
            'name' => 'required',
            'slug' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            'sale_price' => 'numeric',
            'sku' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required|numeric',
            'category_id' => 'required'
        ]);
        if($this->newimage) {
            $this->validateOnly($feilds,[
                'newimage' => 'required|mimes:jpeg,png'
            ]);
        }
    }

    public function updateProduct()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required|numeric',
            'sale_price' => 'numeric',
            'sku' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required|numeric',
            'category_id' => 'required'
        ]);

        if($this->newimage) {
            $this->validate([
                'newimage' => 'required|mimes:jpeg,png'
            ]);
        }

        $product = Product::find($this->product_id);
        $product->name = $this->name;
        $product->slug = $this->slug;
        $product->short_description = $this->short_description;
        $product->description = $this->description;
        $product->regular_price = $this->regular_price;
        $product->sale_price = $this->sale_price;
        $product->SKU = $this->sku;
        $product->stock_status = $this->stock_status;
        $product->featured = $this->featured;
        $product->quantity = $this->quantity;
        if($this->newimage)
        {
            unlink('assets/images/products/'.$product->image);
            $imageName = Carbon::now()->timestamp.'.'.$this->newimage->extension();
            $this->newimage->storeAs('products',$imageName);
            $product->image = $imageName;
        }

        if($this->newimages) {
            $images = explode(",",$product->images);
            foreach($images as $image) {
                unlink('assets/images/products/'.$image);
            }

            $imagesName = '';
            foreach($this->newimages as $key=>$image) {
                $imgName = Carbon::now()->timestamp.$key.'.'.$image->extension();
                $image->storeAS('products',$imgName);
                if($key == 0)
                    $imagesName = $imgName;
                else
                    $imagesName = $imagesName.','.$imgName;
            }
            
            $product->images = $imagesName;
        }

        $product->category_id = $this->category_id;
        $product->save();
        session()->flash('message','Product has been updated');
    }

    public function render()
    {
        $categories = Category::all();
        return view('livewire.admin.admin-edit-product-component',['categories'=>$categories])->layout('layouts.base');
    }
}
