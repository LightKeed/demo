@if($pages > 1)
    <div x-data="appGallery{{ $name }}()">
        <div class="relative overflow-hidden w-full">
            <div
                x-swipe:left="nextSlide()"
                x-swipe:right="prevSlide()"
                id="gallery-{{ $name }}"
                class="gallery relative flex snap-x gap-9 transition-transform ease-in duration-300 mb-10"
                :style="transform"
            >
                {{ $slot }}
            </div>
            <div x-show="apiLoad" class="absolute w-full h-full left-0 top-0 z-50 bg-white/30 backdrop-blur-sm" x-transition></div>
        </div>

        <div class="flex justify-end items-center gap-4">
            <button x-on:click="prevSlide()" x-bind:disabled="apiLoad" class="flex items-cetner justify-center rounded border-[2px] border-blue-600 p-2 text-blue-600 hover:bg-blue-600 hover:text-white transition">
                @svg('tabler-chevron-left', 'w-8 h-8')
            </button>
            <button x-on:click="nextSlide()" x-bind:disabled="apiLoad" class="flex items-cetner justify-center rounded border-[2px] border-blue-600 p-2 text-blue-600 hover:bg-blue-600 hover:text-white transition">
                <div x-show="!apiLoad">
                    @svg('tabler-chevron-right', 'w-8 h-8')
                </div>
                <div x-show="apiLoad" role="status">
                    <svg class="animate-spin h-8 w-8 text-blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </button>
        </div>
    </div>
    <script type="text/javascript">
        function appGallery{{ $name }}() {
            return {
                gallery: document.getElementById('gallery-{{ $name }}'),
                el: document.querySelector('#gallery-{{ $name }} #item{{ $name }}'),
                api: '{{ $route ?? '' }}',
                apiLoad: false,
                pages: {{ $pages ?? 0 }},
                currentPage: 1,
                activeGallery: 1,
                timerGallery: null,
                gap: 36,
                countBlock: {{ $count }},
                transform: 0,
                slides: 4,
                widthGallery: document.querySelector('.gallery').offsetWidth,
                init() {
                    this.timerGallery = setInterval(() => {
                        this.widthGallery = document.querySelector('.gallery').offsetWidth;
                        if (this.widthGallery <= 600) {this.slides = Math.ceil(this.countBlock); this.gap = 36;}
                        else if (this.widthGallery <= 800) {this.slides = Math.ceil(this.countBlock / 2); this.gap = 36;}
                        else if (this.widthGallery <= 1024) {this.slides = Math.ceil(this.countBlock / 3); this.gap = 36;}
                        else {this.slides = Math.ceil(this.countBlock / 4); this.gap = 36;}
                        if(this.slides < this.activeGallery) {this.activeGallery = 1;}
                        this.transformGallery();
                    }, 1000);
                },
                prevSlide() {
                    this.activeGallery = this.activeGallery === 1 ? this.slides : this.activeGallery - 1
                    this.transformGallery();
                },
                async nextSlide() {
                    if(this.pages > 1 && this.currentPage !== this.pages && this.currentPage === this.activeGallery) {
                        this.apiLoad = true;
                        this.currentPage += 1;

                        try {
                            let response = await fetch(this.api + `&page=${this.currentPage}`);
                            let resData = await response.json();
                            const data = resData.data ?? resData;
                            const count = data.length;

                            this.countBlock += count;

                            data.forEach(item => {
                                const newItem = this.el.cloneNode(true);
                                const title = newItem.querySelector('.title');
                                const views = newItem.querySelector('.views');
                                const created = newItem.querySelector('.created');
                                const image = newItem.querySelector('.image');
                                const eventDay = newItem.querySelector('.event-day');
                                const eventMonth = newItem.querySelector('.event-month');
                                const section = newItem.querySelector('.section');
                                const category = newItem.querySelector('.category');

                                const date = new Date(item.created_at);
                                const eventDate = new Date(item.event_start_at);

                                newItem.href = item.route ?? '';
                                if(title) title.innerText = item.title;
                                if(views) views.innerText = item.views;
                                if(created) created.innerText = `${String(date.getDate()).padStart(2, '0')}.${String(date.getMonth() + 1).padStart(2, '0')}.${String(date.getFullYear())}`;
                                if(image) image.src = `{{ route('Media.Image', ['path' => ""]) }}/${item.poster_id ?? item.background_id ?? ''}?h=200`;
                                if(eventDay) eventDay.innerText = item.start_at.day;
                                if(eventMonth) eventMonth.innerText = item.start_at.fullMonth;
                                if(section) section.innerText = item.section.name;
                                if(category) category.innerText = item.category.name;

                                this.gallery.appendChild(newItem);
                            });

                            this.activeGallery += 1;
                            this.transformGallery();
                            this.apiLoad = false;
                        } catch (e) {
                            console.log(e);
                            this.currentPage -= 1;
                            this.apiLoad = false;
                        }
                    } else {
                        this.activeGallery = this.activeGallery === this.slides ? 1 : this.activeGallery + 1;
                        this.transformGallery();
                    }
                },
                selectSlide(slide) {
                    this.activeGallery = slide;
                    this.transformGallery();
                },
                transformGallery() {
                    this.transform = 'transform: translateX(-' + (this.widthGallery + this.gap) * (this.activeGallery - 1) + 'px)';
                }
            }
        }
    </script>
@else
    <div class="grid grid-cols-4 gap-9">
        {{ $slot }}
    </div>
@endif