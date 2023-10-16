<?php

namespace App\Services\Classes\Web;

use App\Services\BaseService;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Article;
use App\Models\News;
use App\Models\Event;

final class HomeService extends BaseService
{
    public function index()
    {
        return View::make('base.index');
    }

    public function show(string $path, string $subPath, string $subChild, string $page)
    {
        $fullUrl = implode( '/', array_filter([$path, $subPath, $subChild, $page]));

        // Проверка альтернативных URL
        if($article = Article::where('slug_alternative', '=', $fullUrl)->first()) {
            return $this->showArticle($article);
        } else if($news = News::where('slug_alternative', '=', $fullUrl)->first()) {
            return $this->showNews($news->slug);
        } else if($event = Event::where('slug_alternative', '=', $fullUrl)->first()) {
            return $this->showEvent($event->slug);
        }

        // Проверка наличия 4-го уровня (всегда страница)
        if($page) {
            return $this->showArticle(
                Article::where('slug', '=', $page)
                    ->whereRelation('category', 'slug', '=', $subChild)
                    ->whereRelation('category.parent', 'slug', '=', $subPath)
                    ->whereRelation('category.parent.parent', 'slug', '=', $path)
                    ->firstOrFail()
            );
        }

        $category = $this->getCategoryBySlug($path);

        if($article = $this->findArticle($subPath, $category->id)) {
            return $this->showArticle($article);
        }

        $subCategory = $subPath ? $category->childrenCategories()->where('slug', '=', $subPath)->firstOrFail() : null;

        if($article = $this->findArticle($subChild, $subCategory ? $subCategory->id : null)) {
            return $this->showArticle($article);
        }

        $childCategory = $subCategory && $subChild ? $subCategory->childrenCategories()->where('slug', '=', $subChild)->firstOrFail() : null;

        return $this->showCategory($category, $subCategory, $childCategory);
    }

    public function search(Request $request)
    {
        $title = $request->title;
        $scope = $request->scope;

        if(strlen($title) < 8) {
            session()->flash('error', 'Минимальная длина запроса 8 символов');
        }

        if($scope === 'news') {
            $news = \App\Services\Classes\Web\NewsService::searchTake($title, 4);
        } else if($scope === 'events') {
            $events = \App\Services\Classes\Web\EventService::searchTake($title, 4);
        } else if($scope === 'directory') {
            // Поиск по справочнику
        } else {
            $categories = \App\Services\Classes\Web\CategoryService::search($title);
            $articles = \App\Services\Classes\Web\ArticleService::searchTake($title, 4);
            $news = \App\Services\Classes\Web\NewsService::searchTake($title, 4);
            $events = \App\Services\Classes\Web\EventService::searchTake($title, 4);
        }

        return View::make('base.search', [
            'categories' => $categories ?? null,
            'articles' => $articles ?? null,
            'news' => $news ?? null,
            'events' => $events ?? null,
        ]);
    }

    private function showCategory($category, $subCategory = null, $childCategory = null)
    {
        if($childCategory && ($article = Article::where('category_id', '=', $childCategory->id)->orderBy('sort_order')->first())) {
            return redirect()->route('HomePage', ['path' => $category->slug, 'subPath' => $subCategory->slug, 'subChild' => $childCategory->slug, 'page' => $article->slug]);
        }

        if($subCategory && $subCategory->childrenCategories->isEmpty() && ($article = Article::where('category_id', '=', $subCategory->id)->orderBy('sort_order')->first())) {
            return redirect()->route('HomeSubChild', ['path' => $category->slug, 'subPath' => $subCategory->slug, 'subChild' => $article->slug]);
        }

        return View::make('article.categories', [
            'category' => $childCategory ?? $subCategory ?? $category,
        ]);
    }

    private function showArticle($article)
    {
        return View::make('article.index', [
            'article' => $article
        ]);
    }

    private function showNews(string $slug) {
        return redirect()->route('NewsIndex', ['path' => $slug]);
    }

    private function showEvent(string $slug) {
        return redirect()->route('EventIndex', ['path' => $slug]);
    }

    private function getCategoryBySlug(string $slug)
    {
        return Category::where('slug', '=', $slug)
            ->with('childrenCategories')
            ->firstOrFail();
    }

    private function findArticle($slug, $category_id)
    {
        return Article::where([
                ['slug', '=', $slug],
                ['category_id', '=', $category_id]
            ])
            ->first();
    }
}
