<div class="container my-14 space-y-14"
     x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? '!text-white' : ''">
    <h2 class="font-extrabold text-4xl leading-tight">Полезное</h2>
    <div class="grid grid-cols-3 gap-9">
        @foreach (json_decode(config('settings.helpful')) ?? [] as $cols)
            <div class="grid gap-9 grid-cols-{{ $cols->gridcols }} col-span-{{ $cols->colspan }}">
                @foreach ($cols->blocks as $block)
                    <div class="col-span-{{ $block->colspan }} row-span-{{ $block->rowspan }}">
                        <div class="space-y-8 @if($block->bgcolor != null) bg-{{ $block->bgcolor }} pb-8 @endif" @if($block->bgcolor != null) x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? '!bg-none !bg-gray-700' : ''" @endif>
                            <img class="object-cover h-[150px] w-full" style="@if(isset($block->media_height)) height: {{ $block->media_height }}px !important; @endif" src="{{ route('Media.Image', ['path' => $block->media_id]) }}"/>
                            <div class="space-y-5 @if($block->textcolor != null) text-{{ $block->textcolor }} @endif @if($block->bgcolor != null) px-8 @endif">
                                <p class="text-2xl font-medium">{{ $block->name }}</p>
                                <div class="grid gap-x-9 gap-y-1 grid-cols-{{ $block->colspan }}">
                                    @foreach ($block->link as $element)
                                        <a href="#" class="block hover:underline">{{ $element->title }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
