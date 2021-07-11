<?php

namespace App\Http\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class AdminSettingComponent extends Component
{
    public $email;
    public $phone;
    public $phone2;
    public $address;
    public $map;
    public $facebook;
    public $pinterest;
    public $youtube;
    public $instagram;
    public $twitter;

    public $setting;

    public function mount() {
        if(count(Setting::get())>0) {
            $this->setting = Setting::first();
            $this->email = $this->setting->email;
            $this->phone = $this->setting->phone;
            $this->phone2 = $this->setting->phone2;
            $this->address = $this->setting->address;
            $this->map = $this->setting->map;
            $this->facebook = $this->setting->facebook;
            $this->pinterest = $this->setting->pinterest;
            $this->youtube = $this->setting->youtube;
            $this->instagram = $this->setting->instagram;
            $this->twitter = $this->setting->twitter;
        }
    }

    public function updated($feilds) {
        $this->validateOnly($feilds,[
            'email'=>'required|email',
            'phone'=>'required',
            'phone2'=>'required',
            'address'=>'required',
            'map'=>'required',
            'facebook'=>'required',
            'pinterest'=>'required',
            'youtube'=>'required',
            'instagram'=>'required',
            'twitter'=>'required',
        ]);
    }
    public function saveSettings() {
        $this->validate([
            'email'=>'required|email',
            'phone'=>'required',
            'phone2'=>'required',
            'address'=>'required',
            'map'=>'required',
            'facebook'=>'required',
            'pinterest'=>'required',
            'youtube'=>'required',
            'instagram'=>'required',
            'twitter'=>'required',
        ]);

        if($this->setting) {
            $this->setting->update([
                'email'=>$this->email,
                'phone'=>$this->phone,
                'phone2'=>$this->phone2,
                'address'=>$this->address,
                'map'=>$this->map,
                'facebook'=>$this->facebook,
                'pinterest'=>$this->pinterest,
                'youtube'=>$this->youtube,
                'instagram'=>$this->instagram,
                'twitter'=>$this->twitter
            ]);
        } else {
            Setting::create([
                'email'=>$this->email,
                'phone'=>$this->phone,
                'phone2'=>$this->phone2,
                'address'=>$this->address,
                'map'=>$this->map,
                'facebook'=>$this->facebook,
                'pinterest'=>$this->pinterest,
                'youtube'=>$this->youtube,
                'instagram'=>$this->instagram,
                'twitter'=>$this->twitter
            ]);
        }

        session()->flash('message','Setting has been saved successfully');
    }
    public function render()
    {
        return view('livewire.admin.admin-setting-component')->layout('layouts.base');
    }
}
