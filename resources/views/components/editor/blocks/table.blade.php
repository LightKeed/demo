<table class="border-collapse border border-slate-500 my-4" {{ isset($tunes->itemProp) ? 'itemprop='.$tunes->itemProp->value : '' }}>
    @foreach($data->content as $row)
        <tr>
            @foreach($row as $col)
                <td class="border border-slate-600">{!! $col !!}</td>
            @endforeach
        </tr>
    @endforeach
</table>
