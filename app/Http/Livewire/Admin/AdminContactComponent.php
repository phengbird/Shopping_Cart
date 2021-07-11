<?php

namespace App\Http\Livewire\Admin;

use App\Models\Contact;
use Livewire\Component;

class AdminContactComponent extends Component
{
    public function render()
    {
        $Contacts = Contact::paginate(12);
        return view('livewire.admin.admin-contact-component',compact('Contacts'))->layout('layouts.base');
    }
}
