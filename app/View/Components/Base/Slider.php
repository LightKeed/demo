<?php

namespace App\View\Components\Base;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Slider extends Component
{
    public $slider;

    /**
     * Create a new component instance.
     */
    public function __construct(int $id)
    {
        $this->slider = \App\Services\Classes\Web\SliderService::getById($id);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.base.slider');
    }
}
