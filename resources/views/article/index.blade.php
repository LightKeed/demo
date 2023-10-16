<x-article.layout>
    <x-slot:title>{{ $article->title }}</x-slot>
    @if($article->slider_id)
        <x-slot:slider_id>{{ $article->slider_id }}</x-slot>
    @else
        <x-slot:background_id>{{ $article->background_id }}</x-slot>
    @endif

    <div class="container my-9 space-y-9">
        <h2 class="font-extrabold text-4xl leading-tight">
            @if(!$article->category->parent)
                {{ $article->title }}
            @else
                {{ $article->category->name }}
            @endif
        </h2>
        @if($article->category->parent)
            <x-article.menu id="{{ $article->category->id }}"/>
        @endif
    </div>

    <div class="container my-9">
        <x-editor.editor :data="$article->text"/>
    </div>
</x-article.layout>
