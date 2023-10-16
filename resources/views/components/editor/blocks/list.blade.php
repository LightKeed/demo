<div class="text-lg my-4">
    <{{ $data->style == 'ordered' ? 'ul' : 'ol' }} style="{{ $data->style == 'ordered' ? 'list-style-type: decimal;' : 'list-style-type: disc;' }} padding-left: 20px;" {{ isset($tunes->itemProp) ? 'itemprop='.$tunes->itemProp->value : '' }}>
        @foreach($data->items as $item)
            <li>{!! $item !!}</li>
        @endforeach
    </{{ $data->style == 'ordered' ? 'ul' : 'ol' }}>
</div>
