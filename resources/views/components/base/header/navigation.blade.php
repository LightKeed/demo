<div
    class="absolute w-full z-[9999] transition-colors"
    x-on:mouseover="overNavigation()"
    x-on:mouseleave="leaveNavigation"
    x-bind:class="[statusNavigation ? 'bg-white' : 'bg-transparent', statusVisualImpaired && infoVisualImpaired.typeColor ? '!bg-gray-800 text-white' : '']">
  <div class="container mx-auto flex items-center justify-between h-24">
    <a href="/" class="flex h-full">
      <img x-show="!statusNavigation || (statusVisualImpaired && infoVisualImpaired.typeColor)" src="/media/images/{{ config('settings.logo') }}" class="my-[18px]">
      <img x-show="statusNavigation && !(statusVisualImpaired && infoVisualImpaired.typeColor)" src="/media/images/{{ config('settings.logo_dark') }}" class="!my-[18px]">
    </a>
    <div class="text-base flex space-x-3" x-bind:class="[statusNavigation ? 'text-gray-900' : 'text-white', statusVisualImpaired && infoVisualImpaired.typeColor ? '!text-white' : '']"> <!-- text-indigo-700 -->
      @foreach($categories as $category)
        <a href="#"
           class="flex items-center group"
           x-on:click="changeCategory({{ $category->id }})">
          <span class="transition-all" x-bind:class="{ 'text-blue-800 border-b-2 border-blue-800' : statusMenu == {{ $category->id }} }">{{ $category->name }}</span>
          <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100 transition-all"
              x-bind:class="{ 'rotate-180 stroke-blue-800 opacity-100' : statusMenu == {{ $category->id }} }"
              width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M6 9l6 6l6 -6"></path>
          </svg>
        </a>
      @endforeach
    </div>
    <div class="flex gap-6">
      <div x-on:click="openSearch()" x-bind:class="statusNavigation ? 'text-gray-800' : 'text-gray-200'" class="flex w-6 h-6 cursor-pointer">
        <svg x-show="!statusSearch" x-transition xmlns="http://www.w3.org/2000/svg" class="absolute w-6 h-6" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
          <path d="M21 21l-6 -6"></path>
        </svg>
        <svg x-show="statusSearch" x-transition xmlns="http://www.w3.org/2000/svg" class="absolute w-6 h-6 stroke-blue-800" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
          <path d="M5.039 5.062a7 7 0 0 0 9.91 9.89m1.584 -2.434a7 7 0 0 0 -9.038 -9.057"></path>
          <path d="M3 3l18 18"></path>
        </svg>
      </div>
      <div x-on:click="openVisualImpaired()" x-bind:class="statusNavigation ? 'text-gray-800' : 'text-gray-200'" class="flex w-6 h-6 cursor-pointer">
        <svg x-show="!statusVisualImpaired" xmlns="http://www.w3.org/2000/svg" class="absolute w-6 h-6" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
          <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
          <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
        </svg>
        <svg x-show="statusVisualImpaired" xmlns="http://www.w3.org/2000/svg" class="absolute w-6 h-6 stroke-blue-800" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
          <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"></path>
          <path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"></path>
          <path d="M3 3l18 18"></path>
        </svg>
      </div>
    </div>
  </div>
</div>
