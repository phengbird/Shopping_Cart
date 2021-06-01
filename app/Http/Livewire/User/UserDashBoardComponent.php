<?php

namespace App\Http\Livewire\User;

use Livewire\Component;

class UserDashBoardComponent extends Component
{
    public function render()
    {
        return view('livewire.user.user-dash-board-component')->layout('layouts.base');
    }
}
