@if($articles && !$articles->isEmpty())
    <div>
        <h2 class="font-normal text-3xl leading-tight mb-4">Страницы</h2>

        <x-base.gallery-card
            name="Article"
            count="{{ count($articles) }}"
            pages="{{ $articles->lastPage() }}"
            route="{{ route('Api.ArticleSearch', ['title' => request()->title]) }}"
        >
            @foreach($articles as $article)
                <a
                    id="itemArticle"
                    href="{{ $article->route }}"
                    class="rounded-lg relative flex-none snap-end w-full {{ $articles->lastPage() > 1 ? 'sm:!w-[calc((100%-36px)/2)] lg:!w-[calc((100%-(36px*2))/3)] xl:!w-[calc((100%-(36px*3))/4)]' : '' }} group-link-underline"
                >
                    <img src="{{ route('Media.Image', ['path' => $article->poster_id ?? $article->background_id ?? ""]) }}?h=200" class="image h-[200px] w-full object-cover rounded mb-5">
                    <div class="bg-blue-600 px-3 py-1 rounded-[20px] text-white max-w-fit mb-5"><p class="category truncate">{{ $article->category->name }}</p></div>
                    <span class="title font-medium text-lg">{{ $article->title }}</span>
                </a>
            @endforeach
        </x-base.gallery-card>
    </div>
@endif