<div>
    <div class="grid gap-4 mb-4 {{ $data->columns == 2 ? 'md:grid-cols-2' : 'grid-cols-1' }}">
        @foreach($data->items as $item)
            <div class="relative group">
                <a class="relative w-full flex items-center gap-2 text-lg !no-underline cursor-pointer" href="/media/{{ $item->path }}" target="_blank">
                    <div class="relative text-blue-600 font-bold select-none">
                        @if($item->extension == 'zip' || $item->extension == 'rar')
                            <svg xmlns="http://www.w3.org/2000/svg" class="min-w-[48px] min-h-[48px]" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 20.735a2 2 0 0 1 -1 -1.735v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-1"></path>
                                <path d="M11 17a2 2 0 0 1 2 2v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-2a2 2 0 0 1 2 -2z"></path>
                                <path d="M11 5l-1 0"></path>
                                <path d="M13 7l-1 0"></path>
                                <path d="M11 9l-1 0"></path>
                                <path d="M13 11l-1 0"></path>
                                <path d="M11 13l-1 0"></path>
                                <path d="M13 15l-1 0"></path>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="min-w-[48px] min-h-[48px]" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                            </svg>
                            <span class="absolute bottom-[-5px] left-0 p-[2px] bg-white leading-none text-base">{{ $item->extension }}</span>
                        @endif
                    </div>
                    <span class="!no-underline group-hover:!underline">{{ $item->fullname }}</span>
                </a>
            </div>
        @endforeach
    </div>
</div>
