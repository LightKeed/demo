<div class="flex space-x-6">
  @foreach (json_decode(config('settings.social')) ?? [] as $social)
    <a href="{{ $social->link }}" class="no-underline">
        @svg('tabler-' . $social->icon, 'w-6 h-6 text-gray-200')
    </a>
  @endforeach
</div>
