<div class="grid grid-cols-1 my-4" x-data="{ open: false }">
    <div class="flex items-center pt-[10px] cursor-pointer select-none" x-on:click="open = ! open" {{ isset($tunes->itemProp) ? 'itemprop='.$tunes->itemProp->value : '' }}>
        <{{ 'h' . $data->title->level }}
        class="{{ $data->title->level == 3 ? 'font-extrabold text-3xl leading-tight' : '' }}{{ $data->title->level == 4 ? 'text-2xl font-medium' : '' }}">
            {!! $data->title->text !!}
        </{{ 'h' . $data->title->level }}>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 flex-none transition ease-in-out delay-50 ml-4" x-bind:class="! open ? '' : 'rotate-180'" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 9l6 6l6 -6"></path>
        </svg>
    </div>
    <div x-show="open" x-transition>
        <x-editor.editor :data="json_encode($data->col)"/>
    </div>
</div>
