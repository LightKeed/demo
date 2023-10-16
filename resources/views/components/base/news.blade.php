<div class="container my-14 space-y-14" x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? 'text-white' : ''">
    <h2 class="font-extrabold text-4xl leading-tight">Новости ТИУ</h2>
    <div class="grid grid-cols-4 gap-9">
        @foreach($news as $element)
            <a href="{{ route('NewsIndex', ['path' => $element->slug]) }}" class="{{ $loop->iteration == 1 ? 'col-span-4 grid grid-cols-2 gap-9' : 'space-y-5' }} group-link-underline">
                <img src="{{ route('Media.Image', ['path' => $element->poster_id ?? $element->background_id ?? ""]) }}?h={{ $loop->iteration == 1 ? '350' : '200' }}" class="{{ $loop->iteration == 1 ? 'h-[350px]' : 'h-[200px]' }} w-full object-cover"/>
                <div class="relative space-y-5 {{ $loop->iteration == 1 ? 'border-l pl-9' : '' }}">
                    @if($loop->iteration == 1)
                        <div class="py-[4px] flex gap-4">
                            @foreach($element->tags as $tag)
                                <a href="/"
                                   class="text-base border-[2px] text-white border-white font-medium rounded-full px-4 py-1 bg-blue-600 border-blue-600"
                                   x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? '!text-gray-900 bg-white' : ''">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                    <span class="title text-2xl font-medium {{ $loop->iteration == 1 ? '' : '!text-lg' }}">{{ $element->title }}</span>
                    @if($loop->iteration == 1)
                        <p class="text-base text-gray-700" x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? '!text-gray-200' : ''">{{ $element->description }}</p>
                    @endif
                    <div class="absolute bottom-0">
                        @if($loop->iteration == 1)
                            <p>{{ $element->created_at->format('d/m/y') }}</p>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
