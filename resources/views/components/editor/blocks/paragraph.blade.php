<div class="flex items-center {{ isset($data->align) ? 'text-'.$data->align : '' }}">
    @if(isset($data->icon) && $data->icon != '')
        {!! $data->icon !!}
    @endif
    <div class="w-full text-lg my-4" {{ isset($tunes->itemProp) ? 'itemprop='.$tunes->itemProp->value : '' }}>{!! $data->text !!}</div>
</div>
