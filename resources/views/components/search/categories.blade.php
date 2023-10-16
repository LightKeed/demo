@if($categories && !$categories->isEmpty())
    <div>
        <h2 class="font-normal text-3xl leading-tight mb-4">Разделы</h2>
        <div class="relative mb-16" x-data="appVerticalGallery()">
            <div class="overflow-hidden h-[494px]">
                <div
                    class="gallery relative snap-y space-y-[10px] lg:space-y-[25px] transition-transform ease-in duration-300 mb-10"
                    :style="transform"
                >
                    @foreach($categories as $category)
                        <a
                            href="{{ $category->route }}"
                            class="group relative block py-14 font-extrabold !bg-cover !bg-no-repeat !bg-center grayscale hover:grayscale-0 transition duration-500 cursor-pointer"
                            style="background: url('{{ route('Media.Image', ['path' => $category->background_id]) }}');"
                        >
                            <div class="absolute w-full h-full top-0 left-0 bg-black/20 blur-sm group-hover:opacity-0 transition duration-500"></div>
                            <span class="relative px-6 block container text-white uppercase text-lg md:text-xl lg:text-2xl xl:text-3xl">
                                {{ $category->name }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end items-center gap-4 mt-8">
                <div x-on:click="prevSlide()" class="flex items-cetner justify-center rounded border-[2px] border-blue-600 p-2 text-blue-600 hover:bg-blue-600 hover:text-white transition cursor-pointer">
                    @svg('tabler-chevron-up', 'w-8 h-8')
                </div>
                <div x-on:click="nextSlide()" class="flex items-cetner justify-center rounded border-[2px] border-blue-600 p-2 text-blue-600 hover:bg-blue-600 hover:text-white transition cursor-pointer">
                    @svg('tabler-chevron-down', 'w-8 h-8')
                </div>
            </div>
        </div>
        <script type="text/javascript">
            function appVerticalGallery() {
                return {
                    activeGallery: 1,
                    timerGallery: null,
                    gap: 25,
                    countBlock: {{ count($categories) }},
                    transform: 0,
                    slides: 4,
                    widthGallery: 494,
                    init() {
                        this.timerGallery = setInterval(() => {
                            this.slides = Math.ceil(this.countBlock / 3);
                            if(this.slides < this.activeGallery) {this.activeGallery = 1;}
                            this.transformGallery();
                        }, 1000);
                    },
                    prevSlide() {
                        this.activeGallery = this.activeGallery === 1 ? this.slides : this.activeGallery - 1
                        this.transformGallery();
                    },
                    nextSlide() {
                        this.activeGallery = this.activeGallery === this.slides ? 1 : this.activeGallery + 1;
                        this.transformGallery();
                    },
                    selectSlide(slide) {
                        this.activeGallery = slide;
                        this.transformGallery();
                    },
                    transformGallery() {
                        this.transform = 'transform: translateY(-' + (this.widthGallery + this.gap) * (this.activeGallery - 1) + 'px)';
                    }
                }
            }
        </script>
    </div>
@endif