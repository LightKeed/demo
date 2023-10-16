<div class="grid grid-cols-{{ count($data->cols) }} gap-8 my-4" {{ isset($tunes->itemProp) ? 'itemprop='.$tunes->itemProp->value : '' }}>
    @foreach($data->cols as $cols)
        <div>
            <x-editor.editor :data="json_encode($cols)"/>
        </div>
    @endforeach
</div>
