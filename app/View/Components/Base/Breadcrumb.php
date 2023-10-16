<?php

namespace App\View\Components\Base;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Category;
use App\Models\Article;

class Breadcrumb extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $items = [];
        $url = explode("/" , explode("?" , $_SERVER['REQUEST_URI'])[0]);

        foreach($url as $el) {
            if($category = Category::where('slug', '=', $el)->first()) { $items[] = ['link' => $category->route, 'title' => $category->name, 'category_id' => $category->id]; continue; }

            if(isset($items[array_key_last($items)]) && isset($items[array_key_last($items)]['category_id']) && ($article = Article::where('slug', '=', $el)->whereRelation('category', 'id', '=', $items[array_key_last($items)]['category_id'])->first())) {
                $items[] = ['link' => $article->route, 'title' => $article->title];
                continue;
            }
        }

        return view('components.base.breadcrumb', [
            'items' => $items
        ]);
    }
}
