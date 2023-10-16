<?php

namespace App\View\Components\Event;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Widget extends Component
{
    public $events;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->events = \App\Services\Classes\Web\EventService::take(3);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.event.widget');
    }
}
