<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserChangePasswordComponent extends Component
{
    public $current_password;
    public $password;
    public $password_confirmation;

    public function updated($fields) {
        $this->validateOnly($fields , [
            'current_password'=>'required',
            'password'=>'required|min:8|confirmed|different:current_password',
        ]);
    }

    public function changePassword() {
        $this->validate([
            'current_password'=>'required',
            'password'=>'required|min:8|confirmed|different:current_password',
        ]);

        if(Hash::check($this->current_password,auth()->user()->password)) {
            User::findOrFail(auth()->user()->id)->update([
                // 'passwor'=>Hash::make($this->password) (or use this)
                'password'=>bcrypt($this->password),
            ]);

            session()->flash('password_message','Password has been changed successfully!');
        } else {
            session()->flash('password_error','Current password does not match!');
        }
    }

    public function render()
    {
        return view('livewire.user.user-change-password-component')->layout('layouts.base');
    }
}
