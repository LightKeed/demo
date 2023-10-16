<div class="relative select-none flex h-[600px]" x-data="appSlider()">
    <x-base.breadcrumb/>
    @foreach($slider->slides ?? [] as $slide)
        <img :src="'{{ route('Media.Image', ['path' => $slide->media_id ?? '']) }}?h=900&w='+widthScreen+'&fit=crop-center'" x-show="activeSlide === {{ $loop->iteration }}" class="absolute top-0 object-cover h-[600px] w-full" x-transition.duration.400ms/>
    @endforeach
    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-zinc-900/80 via-transparent"></div>
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
