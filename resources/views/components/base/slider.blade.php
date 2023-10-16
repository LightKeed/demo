<div class="relative select-none flex h-[900px]" x-data="appSlider()">
    <div x-on:click="prevSlide()" class="relative z-30 w-full h-full bg-gradient-to-r from-zinc-900/80 to-transparent opacity-0 hover:opacity-100 transition-all duration-150 cursor-pointer"></div>
    @foreach($slider->slides ?? [] as $slide)
        <div class="relative flex-none z-40 container h-full flex items-center" x-show="activeSlide === {{ $loop->iteration }}">
            <div class="text-white space-y-6">
                <p class="font-['Montserrat'] font-extrabold text-5xl leading-tight w-4/5">{{ $slide->title }}</p>
                <p class="font-['Montserrat'] text-2xl font-normal w-4/5">{{ $slide->description }}</p>
                <div class="flex pt-10">
                    <a href="{{ $slide->link_button }}" class="flex group text-lg border-[2px] border-white font-medium rounded-full pr-6 pl-4 py-2 hover:bg-blue-600 hover:border-blue-600 transition-colors duration-150">
                        @svg('tabler-chevron-right', 'w-7 h-7 mr-2.5 text-white')
                        {{ $slide->text_button }}
                    </a>
                </div>
            </div>
        </div>
        <img :src="'{{ route('Media.Image', ['path' => isset($slide->media->id) ? $slide->media->id : ""]) }}?h=900&w='+widthScreen+'&fit=crop-center'" x-show="activeSlide === {{ $loop->iteration }}" class="absolute top-0 object-cover h-[900px] w-full" x-transition.duration.400ms/>
    @endforeach
    <div x-on:click="nextSlide()" class="relative z-30 w-full h-full bg-gradient-to-l from-zinc-900/80 to-transparent opacity-0 hover:opacity-100 transition-all duration-150 cursor-pointer"></div>
    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-zinc-900/80 via-transparent"></div>
    <div class="absolute z-40 bottom-8 w-full flex justify-center space-x-4">
        @foreach($slider->slides ?? [] as $slide)
            <div class="w-12 h-2 backdrop-blur-sm bg-white/70 rounded-sm cursor-pointer hover:bg-blue-600/40 transition-[width] duration-150" x-on:click="selectSlide({{ $loop->iteration }})" x-bind:class="{ '!bg-blue-600 !w-24' : activeSlide === {{ $loop->iteration }} }" x-transition></div>
        @endforeach
    </div>
</div>

<script>
    function appSlider() {
        return {
            activeSlide: 1,
            timerSlider: null,
            widthScreen: window.screen.availWidth,
            slides: {{ $slider ? $slider->slides->count() : 0 }},
            init() {
                this.timerSlider = setInterval(() => {
                    this.activeSlide = this.activeSlide === {{ $slider ? $slider->slides->count() : 0 }} ? 1 : this.activeSlide + 1;
                }, 5000);
            },
            resetSlider() {
                clearInterval(this.timerSlider);
                this.init();
            },
            prevSlide() {
                this.activeSlide = this.activeSlide === 1 ? {{ $slider ? $slider->slides->count() : 0 }} : this.activeSlide - 1
                this.resetSlider();
            },
            nextSlide() {
                this.activeSlide = this.activeSlide === {{ $slider ? $slider->slides->count() : 0 }} ? 1 : this.activeSlide + 1;
                this.resetSlider();
            },
            selectSlide(slide) {
                this.activeSlide = slide;
                this.resetSlider();
            }
        }
    }
</script>
