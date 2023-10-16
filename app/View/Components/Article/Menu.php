<?php

namespace App\View\Components\Article;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Menu extends Component
{
    public $articles;
    /**
     * Create a new component instance.
     */
    public function __construct(int $id = null)
    {
        $this->articles = \App\Services\Classes\Web\CategoryService::getById($id)->articles;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.article.menu');
    }
}
