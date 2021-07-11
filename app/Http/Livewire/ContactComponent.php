<?php

namespace App\Http\Livewire;

use App\Models\Contact;
use App\Models\Setting;
use Livewire\Component;
use Ramsey\Collection\Set;

class ContactComponent extends Component
{
    public $name;
    public $email;
    public $phone;
    public $comment;

    public function updated($feilds) {
        $this->validateOnly($feilds,[
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'comment'=>'required',
        ]);
    }

    public function sendMessage() {
        $this->validate([
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'comment'=>'required',
        ]);

        Contact::create([
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'comment'=>$this->comment,
        ]);

        session()->flash('message','Thanks,Your message has been sent successfully');
    }
    public function render()
    {
        $setting = Setting::first();
        return view('livewire.contact-component',compact('setting'))->layout('layouts.base');
    }
}
