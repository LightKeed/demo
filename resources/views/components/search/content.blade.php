<div class="container py-9">
    <x-base.alert/>
    <div class="space-y-6">
        <h2 class="font-extrabold text-4xl leading-tight">Результаты поиска</h2>

        <x-search.categories :categories="$categories"/>
        <x-search.articles :articles="$articles"/>
        <x-search.news :news="$news"/>
        <x-search.events :events="$events"/>

        <h2 class="font-normal text-3xl leading-tight mb-4">Справочник</h2>

        @if($categories->isEmpty() && $articles->isEmpty() && $news->isEmpty() && $events->isEmpty())
            <div class="flex justify-center items-center text-lg font-medium text-gray-900 col-span-4 gap-2">
                @svg('tabler-search', 'w-6 h-6 flex-none')
                По вашему запросу ничего не найдено.
            </div>
        @endif
    </div>
</div>