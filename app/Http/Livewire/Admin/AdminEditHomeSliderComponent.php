<?php

namespace App\Http\Livewire\Admin;

use App\Models\HomeSlider;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class AdminEditHomeSliderComponent extends Component
{
    use WithFileUploads;

    public $title;
    public $subtitle;
    public $price;
    public $image;
    public $status;
    public $link;

    public $newimage;
    public $slider_id;

    public function mount($slider_id)
    {
        $slider = HomeSlider::find($slider_id);
        $this->title = $slider->title;
        $this->subtitle = $slider->subtitle;
        $this->price = $slider->price;
        $this->status = $slider->status;
        $this->link = $slider->link;
        $this->slider_id = $slider->id;
        $this->image = $slider->image;
    }

    public function updateSlider()
    {
        $slider = HomeSlider::find($this->slider_id);
        $slider->title = $this->title;
        $slider->subtitle = $this->subtitle;
        $slider->price = $this->price;
        $slider->status = $this->status;
        $slider->link = $this->link;
        if($this->newimage)
        {
            $imageName = Carbon::now()->timestamp.'.'.$this->newimage->extension();
            $this->newimage->storeAs('sliders',$imageName);
            $slider->image = $imageName;
        }
        $slider->save();
        session()->flash('message','Home Slider has been updated');
    }
    public function render()
    {
        return view('livewire.admin.admin-edit-home-slider-component')->layout('layouts.base');
    }
}
