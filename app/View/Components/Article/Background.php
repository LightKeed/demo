<?php

namespace App\View\Components\Article;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Background extends Component
{
    public $background_id;
    /**
     * Create a new component instance.
     */
    public function __construct(int|string $backgroundId)
    {
        $this->background_id = $backgroundId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.article.background');
    }
}
