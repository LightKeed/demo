<?php

namespace App\View\Components\Article;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Slider extends Component
{
    public $slider;
    /**
     * Create a new component instance.
     */
    public function __construct(int|string $sliderId)
    {
        $this->slider = \App\Services\Classes\Web\SliderService::getById($sliderId);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.article.slider');
    }
}
