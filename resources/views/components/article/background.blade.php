<div class="relative select-none flex h-[600px]">
    <x-base.breadcrumb/>
    <img src="{{ route('Media.Image', ['path' => $background_id != '' ? $background_id : '']) }}" class="absolute top-0 object-cover h-[600px] w-full"/>
    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-zinc-900/80 via-transparent"></div>
</div>
