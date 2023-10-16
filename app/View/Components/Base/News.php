<?php

namespace App\View\Components\Base;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class News extends Component
{
    public $news;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->news = \App\Services\Classes\Web\NewsService::take(5);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.base.news');
    }
}
