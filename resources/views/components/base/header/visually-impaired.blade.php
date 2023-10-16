<div
    class="relative w-full z-[9999] bg-white overflow-hidden transition-all duration-300 ease-in-out select-none"
    x-bind:class="statusVisualImpaired ? 'h-[108px]' : 'h-0'">
  <div class="container mx-auto flex justify-between">
    <div class="py-8 flex gap-12">
      <div class="flex items-center gap-6">
        <p class="text-xl font-semibold">Шрифт:</p>
        <div class="pt-3.5 pr-2.5 pb-1.5 pl-2.5 bg-white border-2 border-gray-900">
          <svg xmlns="http://www.w3.org/2000/svg" class="text-gray-900 w-5 h-5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M4 20l3 0"></path>
            <path d="M14 20l7 0"></path>
            <path d="M6.9 15l6.9 0"></path>
            <path d="M10.2 6.3l5.8 13.7"></path>
            <path d="M5 20l6 -16l2 0l7 16"></path>
          </svg>
        </div>
        <div class="p-1.5 bg-white border-2 border-gray-900">
          <svg xmlns="http://www.w3.org/2000/svg" class="text-gray-900 w-7 h-7" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M4 20l3 0"></path>
            <path d="M14 20l7 0"></path>
            <path d="M6.9 15l6.9 0"></path>
            <path d="M10.2 6.3l5.8 13.7"></path>
            <path d="M5 20l6 -16l2 0l7 16"></path>
          </svg>
        </div>
      </div>
      <div class="flex items-center gap-6">
        <p class="text-xl font-semibold">Цвет:</p>
        <div
            class="p-1.5 border-2 border-gray-900 cursor-pointer"
            x-on:click="infoVisualImpaired.typeColor = !infoVisualImpaired.typeColor"
            x-bind:class="infoVisualImpaired.typeColor ? 'bg-gray-900 text-white' : 'bg-white'">
          @svg('tabler-brightness', 'w-7 h-7')
        </div>
      </div>
      <div class="flex items-center gap-6">
        <p class="text-xl font-semibold">Изображения:</p>
        <div
            class="border-2 p-1.5 border-gray-900 cursor-pointer transition-all"
            x-on:click="infoVisualImpaired.hiddenImages = false"
            x-bind:class="!infoVisualImpaired.hiddenImages ? 'bg-gray-900' : 'bg-white'">
          <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-7 h-7 transition-all"
              x-bind:class="!infoVisualImpaired.hiddenImages ? 'text-white' : 'text-gray-900'"
              width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M15 8h.01"></path>
            <path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z"></path>
            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5"></path>
            <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3"></path>
          </svg>
        </div>
        <div
            class="border-2 p-1.5 border-gray-900 cursor-pointer transition-all"
            x-on:click="infoVisualImpaired.hiddenImages = true"
            x-bind:class="infoVisualImpaired.hiddenImages ? 'bg-gray-900' : 'bg-white'">
          <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-7 h-7 transition-all"
              x-bind:class="infoVisualImpaired.hiddenImages ? 'text-white' : 'text-gray-900'"
              width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M15 8h.01"></path>
            <path d="M7 3h11a3 3 0 0 1 3 3v11m-.856 3.099a2.991 2.991 0 0 1 -2.144 .901h-12a3 3 0 0 1 -3 -3v-12c0 -.845 .349 -1.608 .91 -2.153"></path>
            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5"></path>
            <path d="M16.33 12.338c.574 -.054 1.155 .166 1.67 .662l3 3"></path>
            <path d="M3 3l18 18"></path>
          </svg>
        </div>
      </div>
      <div class="flex items-center gap-6">
        <p class="text-xl font-semibold">Кернинг:</p>
        <div class="p-1.5 bg-white border-2 border-gray-900">
          @svg('tabler-multiplier-1x', 'text-gray-900 w-7 h-7')
        </div>
        <div class="p-1.5 bg-white border-2 border-gray-900">
          @svg('tabler-multiplier-1-5x', 'text-gray-900 w-7 h-7')
        </div>
        <div class="p-1.5 bg-white border-2 border-gray-900">
          @svg('tabler-multiplier-2x', 'text-gray-900 w-7 h-7')
        </div>
      </div>
    </div>
  </div>
</div>
