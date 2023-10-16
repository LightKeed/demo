@if($news && !$news->isEmpty())
    <div>
        <h2 class="font-normal text-3xl leading-tight mb-4">Новости</h2>

        <x-base.gallery-card
            name="News"
            count="{{ count($news) }}"
            pages="{{ $news->lastPage() }}"
            route="{{ route('Api.NewsSearch', ['title' => request()->title]) }}"
        >
            @foreach($news as $item)
                <a
                    id="itemNews"
                    href="{{ route('NewsIndex', ['path' => $item->slug]) }}"
                    class="rounded-lg relative flex-none snap-end w-full {{ $news->lastPage() > 1 ? 'sm:!w-[calc((100%-36px)/2)] lg:!w-[calc((100%-(36px*2))/3)] xl:!w-[calc((100%-(36px*3))/4)]' : '' }} group-link-underline"
                >
                    <img src="{{ route('Media.Image', ['path' => $item->poster_id ?? $item->background_id ?? ""]) }}?h=200" class="image h-[200px] w-full object-cover rounded mb-5">
                    <div class="relative space-y-5">
                        <div class="bg-blue-600 px-3 py-1 rounded-[20px] max-w-fit text-white"><p class="section truncate">{{ $item->section->name }}</p></div>
                        <div class="flex flex-wrap gap-2 justify-between">
                            <p class="flex items-center gap-1 text-gray-400 font-medium text-sm">
                                @svg('tabler-eye-check', 'w-5 h-5 flex-none')
                                <span class="views">{{ $item->views }}</span>
                            </p>
                            <p class="created text-gray-400 font-medium text-sm">{{ $item->created_at->format('d.m.Y') }}</p>
                        </div>
                        <span class="title text-lg font-medium">{{ $item->title }}</span>
                    </div>
                </a>
            @endforeach
        </x-base.gallery-card>
    </div>
@endif