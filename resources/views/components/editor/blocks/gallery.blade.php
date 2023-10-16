<div class="flex gap-[16px]">
    @foreach($data->media as $url)
        <img
            class="object-cover"
            src="{{ route('Media.Image', ['path' => $url->id]) }}"
            style="width: calc( ( 100% - ( {{ count($data->media) }} - 1 ) * 16px ) / {{ count($data->media) }}"/>
    @endforeach
</div>
