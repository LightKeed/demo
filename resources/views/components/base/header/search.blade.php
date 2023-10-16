<div
    class="absolute top-[144px] w-full z-50 bg-gradient-to-r from-sky-500 to-indigo-700 transition-all duration-300 ease-in-out"
    style="transform: translateY(calc(-100% - 144px));"
    x-bind:class="[statusSearch ? '!translate-y-0 shadow-lg' : 'shadow-none', statusVisualImpaired ? 'top-[252px]' : 'top-[144px]']"
    x-data="{ scope: '{{ request()->scope ?? null }}' }"
  >
  <div class="container mx-auto">
    <div class="py-12">
      <form id="formHSearch">
        <input id="scope" name="scope" type="text" class="hidden" x-model="scope">
        <div class="flex">
          <div class="w-[2px] h-auto bg-white flex-none mr-8"></div>
          <input value="{{ request()->title }}" id="searchTitle" name="title" type="text" class="border-none py-3 outline-none bg-transparent text-5xl text-white font-medium w-full placeholder:text-white/80" placeholder="Пишите для поиска..."/>
        </div>
        <input type="submit" class="hidden">
      </form>
      <div class="flex gap-6 text-white mt-12">
        <a @click="scope = null" class="flex text-lg border-[2px] border-white font-medium rounded-full pr-4 pl-4 py-2 hover:bg-white hover:border-white hover:text-blue-600 transition-colors duration-150 cursor-pointer" :class="{ '!bg-white !text-blue-600': !scope }">
          Искать везде
        </a>
        <a @click="scope = 'news'" class="flex text-lg border-[2px] border-white font-medium rounded-full pr-4 pl-4 py-2 hover:bg-white hover:border-white hover:text-blue-600 transition-colors duration-150 cursor-pointer" :class="{ '!bg-white !text-blue-600': scope == 'news' }">
          Новости
        </a>
        <a @click="scope = 'events'" class="flex text-lg border-[2px] border-white font-medium rounded-full pr-4 pl-4 py-2 hover:bg-white hover:border-white hover:text-blue-600 transition-colors duration-150 cursor-pointer" :class="{ '!bg-white !text-blue-600': scope == 'events' }">
          Мероприятия
        </a>
        <a @click="scope = 'directory'" class="flex text-lg border-[2px] border-white font-medium rounded-full pr-4 pl-4 py-2 hover:bg-white hover:border-white hover:text-blue-600 transition-colors duration-150 cursor-pointer" :class="{ '!bg-white !text-blue-600': scope == 'directory' }">
          Справочник
        </a>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    const searchURL = '{{ route('SearchIndex') }}';
    const formHSearch = document.getElementById('formHSearch');
    const searchHValues = ['title', 'scope'];

    formHSearch.addEventListener('submit', event => {
      event.preventDefault();

      const filterURL = new URL(searchURL);
      const target = event.target;
      console.log(target)

      searchHValues.forEach(val => {
        const input = target.querySelector(`[name=${val}]`);
        let params = [];

        if(input.value) {
          params.push(input.value);
        }

        if(params.length) {
          filterURL.searchParams.append(val, params.join(';'));
        }
      });

      window.location = filterURL;
    });
  </script>
</div>
