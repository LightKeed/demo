@php
    use App\Services\Classes\Web\NewsService;
    use App\Services\Classes\Web\ThematicSectionService;

    $result = [];
    $section = $data->section;

    $title = ThematicSectionService::getByID($section);
    $news = NewsService::takeByThematicSection(4, $section);
@endphp

<div class="container my-14" x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? 'text-white' : ''">
  <h2 class="font-extrabold text-4xl leading-tight mb-12">Новости {{ $title->name }}</h2>
  <div class="grid grid-cols-4 gap-9">
    @foreach($news as $element)
      <a href="{{ route('NewsIndex', ['path' => $element->slug]) }}" class="space-y-5">
        <img src="{{ route('Media.Image', ['path' => $element->poster_id ?? $element->background_id ?? ""]) }}?h=200" class="h-[200px] w-full object-cover"/>
        <div class="relative space-y-5">
          <p class="!text-lg font-medium">{{ $element->title }}</p>
        </div>
      </a>
    @endforeach
  </div>
  <div class="flex justify-end mt-6">
    <a href="/#" class="!no-underline flex group text-lg border-[2px] font-medium text-blue-600 rounded-full pr-6 pl-4 py-2 hover:bg-blue-600 hover:text-white border-blue-600 transition-colors duration-150">
      <svg class="w-7 h-7 mr-2.5 text-blue-600 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 6l6 6l-6 6"></path>
      </svg>
      Смотреть все
    </a>
  </div>
</div>

