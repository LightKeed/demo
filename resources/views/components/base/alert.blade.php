@if (session('status'))
    <div id="alert" class="relative p-4 pr-12 mb-4 text-sm md:text-base text-green-800 border border-green-900 rounded-lg bg-green-100" role="alert">
        {{ session('status') }}
        <a class="absolute top-4 right-4 cursor-pointer" onclick="this.closest('#alert').remove()">
            @svg('tabler-x', 'w-5 h-5')
        </a>
    </div>
@endif

@if ($errors->any() || session('error'))
    <div id="alert" class="relative p-4 pr-12 mb-4 text-sm md:text-base text-red-800 border border-red-900 rounded-lg bg-red-100" role="alert">
        <ul class="pl-5 list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            @if(session('error'))
                <li>{{ session('error') }}</li>
            @endif
        </ul>
        <a class="absolute top-4 right-4 cursor-pointer" onclick="this.closest('#alert').remove()">
            @svg('tabler-x', 'w-5 h-5')
        </a>
    </div>
@endif