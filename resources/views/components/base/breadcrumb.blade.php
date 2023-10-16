<div class="relative z-40 container h-full">
    <div class="text-white space-y-6 h-full">
        <div class="absolute top-28 space-x-2 flex text-gray-200/70">
            @foreach($items as $item)
                @if($loop->remaining == 1)
                    <div class="flex items-center gap-2 md:hidden">
                        @svg('tabler-chevron-right', 'w-5 h-5 mt-px pt-px text-gray-200/80')
                        <a href="{{ $item['link'] }}" class="hover:underline hover:text-white">{{ $item['title'] }}</a>
                    </div>
                @endif
                <div class="hidden md:flex items-center gap-2">
                    @if(!$loop->first)
                        @svg('tabler-chevron-right', 'w-5 h-5 mt-px pt-px text-gray-200/80')
                    @endif

                    <a href="{{ $item['link'] }}" class="hover:underline hover:text-white">{{ $item['title'] }}</a>
                </div>
            @endforeach
        </div>
    </div>
</div>