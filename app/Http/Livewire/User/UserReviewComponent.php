<?php

namespace App\Http\Livewire\User;

use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Auth\Events\Validated;
use Livewire\Component;

class UserReviewComponent extends Component
{
    public $OrderItemId;
    public $Rating;
    public $Comment;

    public function mount($id){
        $this->OrderItemId = $id;
    }

    public function updated($fields) {
        $this->validateOnly($fields,[
            'Rating'=>'required',
            'Comment'=>'required',
        ]);
    }

    public function addReview() {
        $this->validate([
            'Rating'=>'required',
            'Comment'=>'required',
        ]);

        Review::create([
            'rating'=>$this->Rating,
            'comment'=>$this->Comment,
            'order_item_id'=>$this->OrderItemId
        ]);

        OrderItem::findOrFail($this->OrderItemId)->update([
            'review_status'=>1,
        ]);

        session()->flash('message','Your review has been added successfully!');
    }

    public function render()
    {
        $OrderItem = OrderItem::findOrFail($this->OrderItemId);
        return view('livewire.user.user-review-component',compact('OrderItem'))->layout('layouts.base');
    }
}
