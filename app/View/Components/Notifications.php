<?php

namespace App\View\Components;

use App\Notification;
use Illuminate\View\Component;

class Notifications extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        return view('components.notifications',[
			'notifications' => Notification::where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get()
        ]);
    }
}
