@if($style == 'ordered')
    <ol class="pl-8 [counter-reset:section] list-decimal">
@else
    <ul class="pl-8 [counter-reset:section] list-disc">
@endif
    @foreach($list as $item)
        <li class="[counter-increment:section] marker:[content:counters(section,'.')'__']">
            {!! $item->content !!}
            @if(count($item->items) > 0)
                <x-editor.blocks.listItem :list="$item->items" :style="$style"/>
            @endif
        </li>
    @endforeach
@if($style == 'ordered')
    </ol>
@else
    </ul>
@endif