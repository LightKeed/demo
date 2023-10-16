<div class="bg-gradient-to-r from-sky-500 to-indigo-700 text-4xl" x-data="{ scope: '{{ request()->scope ?? null }}' }">
    <div class="container pt-28 pb-14">
        <form id="formSearch">
            <input id="scope" name="scope" type="text" class="hidden" x-model="scope">
            <div class="flex">
                <div class="w-[2px] h-auto bg-white flex-none mr-8"></div>
                <input value="{{ request()->title }}" id="title" name="title" type="text" class="border-none py-3 outline-none bg-transparent text-lg text-white font-medium w-full placeholder:text-white/80" placeholder="Введите запрос..." required>
            </div>
            <input type="submit" class="hidden">
        </form>
        <div class="flex flex-wrap gap-4 text-white mt-6">
            <a @click="scope = null" class="flex border-[2px] border-white text-base font-medium rounded-full px-3 py-1 hover:bg-white hover:border-white hover:text-blue-600 transition-colors duration-150 cursor-pointer" :class="{ '!bg-white !text-blue-600': !scope }">
                Искать везде
            </a>
            <a @click="scope = 'news'" class="flex border-[2px] border-white text-base font-medium rounded-full px-3 py-1 hover:bg-white hover:border-white hover:text-blue-600 transition-colors duration-150 cursor-pointer" :class="{ '!bg-white !text-blue-600': scope == 'news' }">
                Новости
            </a>
            <a @click="scope = 'events'" class="flex border-[2px] border-white text-base font-medium rounded-full px-3 py-1 hover:bg-white hover:border-white hover:text-blue-600 transition-colors duration-150 cursor-pointer" :class="{ '!bg-white !text-blue-600': scope == 'events' }">
                Мероприятия
            </a>
            <a @click="scope = 'directory'" class="flex border-[2px] border-white text-base font-medium rounded-full px-3 py-1 hover:bg-white hover:border-white hover:text-blue-600 transition-colors duration-150 cursor-pointer" :class="{ '!bg-white !text-blue-600': scope == 'directory' }">
                Справочник
            </a>
        </div>
    </div>
    <script type="text/javascript">
        const baseURL = '{{ route('SearchIndex') }}';
        const formSearch = document.getElementById('formSearch');
        const searchValues = ['title', 'scope'];

        formSearch.addEventListener('submit', event => {
            event.preventDefault();

            const filterURL = new URL(baseURL);
            const target = event.target;

            searchValues.forEach(val => {
                const input = target.querySelector(`#${val}`);
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