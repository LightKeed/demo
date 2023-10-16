@foreach($categories as $category)
  <div
      class="absolute top-[144px] w-full z-50 bg-white transition-all duration-300 ease-in-out"
      style="transform: translateY(calc(-100% - 144px));"
      x-bind:class="[statusMenu == {{ $category->id }} ? '!translate-y-0 shadow-lg' : 'shadow-none', statusVisualImpaired ? 'top-[252px]' : 'top-[144px]', statusVisualImpaired && infoVisualImpaired.typeColor ? '!bg-gray-800 text-white' : '']">
    <div class="container mx-auto">
      <div class="grid grid-cols-3 gap-9 border-t py-8">
        @if($category->articles->count() > 0)
          <div class="text-base text-gray-500 col-span-3 grid grid-cols-4 gap-1" x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? '!text-gray-300' : ''">
            @foreach($category->articles as $article)
              <a href="/{{ $category->slug }}/{{ $article->slug }}" class="block hover:underline">{{ $article->title }}</a>
            @endforeach
          </div>
        @endif
        @foreach($category->childrenCategories as $subcategory)
          <div class="space-y-4" x-bind:class="{ 'col-span-3' : 4 <= {{ $subcategory->childrenCategories->count() }}, 'col-span-2' : 2 <= {{ $subcategory->childrenCategories->count() }} && 4 > {{ $subcategory->childrenCategories->count() }} }">
            <div class="space-y-2">
              <p class="text-xl font-medium text-gray-900" x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? '!text-white' : ''">{{ $subcategory->name }}</p>
              @if($subcategory->articles->count() >= 0)
                <div class="text-base text-gray-500 space-y-1" x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? '!text-gray-200' : ''">
                  @foreach($subcategory->articles as $article)
                    <a href="/{{ $category->slug }}/{{ $subcategory->slug  }}/{{ $article->slug }}" class="block hover:underline">{{ $article->title }}</a>
                  @endforeach
                </div>
              @endif
            </div>
            <div x-bind:class="{ 'grid grid-cols-4 gap-6' : 4 <= {{ $subcategory->childrenCategories->count() }}, 'grid grid-cols-2 gap-6' : 2 <= {{ $subcategory->childrenCategories->count() }} && 4 > {{ $subcategory->childrenCategories->count() }} }">
              @foreach($subcategory->childrenCategories as $subcategory1)
                <div class="space-y-4">
                  <div class="space-y-2">
                    <p class="text-lg font-medium text-gray-600" x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? '!text-gray-100' : ''">{{ $subcategory1->name }}</p>
                    @if($subcategory1->articles->count() >= 0)
                      <div class="text-base text-gray-500 space-y-1" x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? '!text-gray-200' : ''">
                        @foreach($subcategory1->articles as $article)
                          <a href="/{{ $category->slug }}/{{ $subcategory->slug  }}/{{ $subcategory1->slug  }}/{{ $article->slug }}" class="block hover:underline">{{ $article->title }}</a>
                        @endforeach
                      </div>
                    @endif
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endforeach
