<div
    class="h-12 relative z-[9999] w-full"
    x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? 'bg-gray-700 text-white' : 'bg-slate-100 text-gray-700'">
  <div class="container mx-auto flex justify-between">
    <div class="flex space-x-8 py-[14px]">
      @foreach(json_decode(config('settings.service_link'))->left ?? [] as $left)
        <a class="text-sm flex space-x-2 hover:underline" target="_blank" href="{{ $left->link }}">
          @svg('tabler-' . $left->icon, 'w-5 h-5')
          <p>{{ $left->title }}</p>
        </a>
      @endforeach
    </div>
    <div class="flex space-x-8 py-[14px]">
      @foreach(json_decode(config('settings.service_link'))->right ?? [] as $right)
        <a class="text-sm flex space-x-2 hover:underline" target="_blank" href="{{ $right->link }}">
          @svg('tabler-' . $right->icon, 'w-5 h-5')
          <p>{{ $right->title }}</p>
        </a>
      @endforeach
      @if(Auth::check())
        <a class="text-sm flex space-x-2 hover:underline" target="_blank" href="/admin">
          @svg('tabler-user', 'w-5 h-5')
          <p>{{ Auth::user()->email }}</p>
        </a>
      @endif
    </div>
  </div>
</div>
