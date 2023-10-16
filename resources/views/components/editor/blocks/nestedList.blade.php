<div class="text-lg my-4">
    @if($data->style == 'ordered')
        <ol
            {{ isset($tunes->itemProp) ? 'itemprop='.$tunes->itemProp->value : '' }}
            class="pl-5 [counter-reset:section] list-decimal"
        >
    @else
        <ul
            {{ isset($tunes->itemProp) ? 'itemprop='.$tunes->itemProp->value : '' }}
            class="pl-5 [counter-reset:section] list-disc"
        >
    @endif
        @foreach($data->items as $item)
            <li class="[counter-increment:section] marker:[content:counters(section,'.')'__']">
                {!! $item->content !!}
                @if(count($item->items) > 0)
                    <x-editor.blocks.listItem :list="$item->items" :style="$data->style"/>
                @endif
            </li>
        @endforeach
    @if($data->style == 'ordered')
        </ol>
    @else
        </ul>
    @endif
</div>
