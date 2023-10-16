<x-article.layout>
    <x-slot:title>{{ $category->name }}</x-slot>
    <x-slot:background_id>{{ $category->background_id }}</x-slot>

    <div class="container my-9 space-y-9">
        <h2 class="font-extrabold text-4xl leading-tight">{{ $category->name }}</h2>
    </div>
    <div class="flex flex-col gap-1 mb-14">
        @foreach($category->childrenCategories as $item)
            <a
                href="{{ $item->route }}"
                class="group relative block py-14 font-extrabold !bg-cover !bg-no-repeat !bg-center grayscale hover:grayscale-0 transition duration-500 cursor-pointer"
                style="background: url('{{ route('Media.Image', ['path' => $item->background_id]) }}');"
            >
                <div class="absolute w-full h-full top-0 left-0 bg-black/20 blur-sm group-hover:opacity-0 transition duration-500"></div>
                <span class="relative block container text-white uppercase text-lg md:text-xl lg:text-2xl xl:text-3xl">
                    {{ $item->name }}
                </span>
            </a>
        @endforeach
    </div>
</x-article.layout>
