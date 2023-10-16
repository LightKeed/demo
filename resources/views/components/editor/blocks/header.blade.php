<{{ 'h' . $data->level }}
{{ isset($tunes) ? ($tunes->itemProp->value != 'undefined' && $tunes->itemProp->value != 'null' ? 'itemprop=' . $tunes->itemProp->value : '') : '' }}
class="{{ $data->level == 2 ? 'font-normal text-4xl leading-tight' : 'font-normal text-3xl leading-tight' }}{{ isset($data->align) ? 'text-'.$data->align : '' }} pt-[10px]">
    {{ $data->text }}
</{{ 'h' . $data->level }}>
