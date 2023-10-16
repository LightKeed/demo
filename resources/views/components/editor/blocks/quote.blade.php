<div class="border-y py-6 border-blue-600 my-4">
    <p class="text-xl font-medium">{!! $data->text !!}</p>
    @if($data->caption != null)
        <p class="{{ $data->alignment == 'left' ? 'text-right' : '' }} mt-4">{!! $data->caption !!}</p>
    @endif
</div>
