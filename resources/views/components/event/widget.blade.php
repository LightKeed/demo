<div class="container my-14 space-y-14" x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? '!text-white' : ''">
    <h2 class="font-extrabold text-4xl leading-tight">Будь с нами</h2>
    <div class="grid grid-cols-3 gap-9">
        @foreach($events as $event)
            <a href="{{ route('EventIndex', ['path' => $event->slug]) }}" class="group-link-underline">
                <span class="title text-2xl font-medium">{{  $event->title  }}</span>
                <p class="font-extrabold text-5xl my-6">{{ $event->start_at['day'] }}<span class="ml-4 font-extrabold text-2xl">{{ $event->start_at['fullMonth'] }}</span></p>
                <img src="{{ route('Media.Image', ['path' => $event->poster_id ?? $event->background_id ?? ""]) }}?h=400" class="h-[400px] w-full object-cover"/>
                <p class="hidden">{{  $event->slug  }}</p>
            </a>
        @endforeach
    </div>
</div>
