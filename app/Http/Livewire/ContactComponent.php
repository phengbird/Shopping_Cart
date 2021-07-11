<?php

namespace App\Http\Livewire;

use App\Models\Contact;
use Livewire\Component;

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
        return view('livewire.contact-component')->layout('layouts.base');
    }
}
