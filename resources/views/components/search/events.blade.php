@if($events && !$events->isEmpty())
    <div>
        <h2 class="font-normal text-3xl leading-tight mb-4">Мероприятия</h2>
        <x-base.gallery-card
            name="Event"
            count="{{ count($events) }}"
            pages="{{ $events->lastPage() }}"
            route="{{ route('Api.EventSearch', ['title' => request()->title]) }}"
        >
            @foreach($events as $event)
                <a
                        id="itemEvent"
                        href="{{ route('EventIndex', ['path' => $event->slug]) }}"
                        class="rounded-lg relative flex-none snap-end w-full {{ $events->lastPage() > 1 ? 'sm:!w-[calc((100%-36px)/2)] lg:!w-[calc((100%-(36px*2))/3)] xl:!w-[calc((100%-(36px*3))/4)]' : '' }} group-link-underline"
                >
                    <div class="relative space-y-5 mb-5">
                        <span class="title text-lg font-medium">{{ $event->title }}</span>
                        <p class="font-extrabold text-5xl my-6">
                            <span class="event-day">{{ $event->start_at['day'] }}</span>
                            <span class="event-month ml-4 font-extrabold text-2xl">{{ $event->start_at['fullMonth'] }}</span>
                        </p>
                    </div>
                    <img src="{{ route('Media.Image', ['path' => $event->poster_id ?? $event->background_id ?? ""]) }}?h=400" class="image h-[400px] w-full object-cover rounded">
                </a>
            @endforeach
        </x-base.gallery-card>
    </div>
@endif