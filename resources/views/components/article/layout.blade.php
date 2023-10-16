<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('settings.app_name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite('resources/css/app.css')

    <!-- Scripts -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/flowbite.min.js"></script>
    <script>
        customElements.define("load-file", class extends HTMLElement {
            async connectedCallback(
                src = this.getAttribute("src"),
                shadowRoot = this.shadowRoot || this.attachShadow({mode:"open"})
            ) {
                shadowRoot.innerHTML = await (await fetch(src)).text()
                shadowRoot.append(...this.querySelectorAll("[shadowRoot]"))
                this.hasAttribute("replaceWith") && this.replaceWith(...shadowRoot.childNodes)
            }
        })
    </script>
  </head>
  <body x-data="app()"
        class="antialiased relative transition-all inversion"
        x-bind:class="[statusVisualImpaired && infoVisualImpaired.hiddenImages ? 'hidden-images' : 'visual-images', statusVisualImpaired && infoVisualImpaired.typeColor ? 'bg-gray-900' : 'bg-white']">
    <x-base.header/>
    @if(isset($slider_id))
      <x-article.slider :slider_id="$slider_id"/>>
    @elseif(isset($background_id))
      <x-article.background :background_id="$background_id"/>
    @endif

    {{ $slot }}
    <x-base.footer/>
    <script>
        function app() {
            return {
                statusNavigation: false,
                statusMenu: false,
                statusSearch: false,
                statusVisualImpaired: false,
                infoVisualImpaired: {
                    typeText: 1,
                    typeColor: false,
                    hiddenImages: false,
                    typeKerning: 1
                },
                changeCategory(item) {
                    this.statusSearch = false;
                    if(this.statusMenu == item) {
                        this.statusMenu = false;
                    } else {
                        this.statusMenu = item;
                    }
                },
                overNavigation() {
                    this.statusNavigation = true;
                },
                leaveNavigation() {
                    if(!this.statusMenu && !this.statusSearch && !this.statusVisualImpaired) {
                        this.statusNavigation = false;
                    }
                },
                closeFull() {
                    this.statusMenu = this.statusSearch = false;
                    if (!this.statusVisualImpaired) {
                        setTimeout(() => this.statusNavigation = false, 200);
                    }
                },
                openSearch() {
                    this.statusMenu = false;
                    if(!this.statusSearch) {
                        this.statusSearch = true;
                        document.getElementById("search").focus();
                    } else { this.statusSearch = false; }
                },
                openVisualImpaired() {
                    if(!this.statusVisualImpaired) {
                        this.statusNavigation = this.statusVisualImpaired = true;
                    } else { this.statusVisualImpaired = false; }
                },
                scrollTop() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }
        }
    </script>
  </body>
</html>
