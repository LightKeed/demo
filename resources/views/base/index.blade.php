<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('settings.app_name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite('resources/css/app.css')

    <!-- Scripts -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <x-base.silent-sso/>
  </head>
  <body
    x-data="app()"
    class="antialiased relative transition-all inversion"
    x-bind:class="[statusVisualImpaired && infoVisualImpaired.hiddenImages ? 'hidden-images' : 'visual-images', statusVisualImpaired && infoVisualImpaired.typeColor ? 'bg-gray-900' : 'bg-white']">
    <x-base.header/>
    <x-base.slider id="1"/>
    <x-base.helpful/>
    <div class="bg-gradient-to-r from-sky-500 to-indigo-700 py-8 my-14"
         x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? '!bg-none !bg-gray-700' : ''">
      <div class="container mx-auto grid grid-cols-3 gap-9">
        <div class="flex items-center space-x-6">
          @svg('tabler-history', 'w-9 h-9 text-gray-200')
          <div>
            <p class="text-base text-gray-200">Более 20 лет развития</p>
            <p class="text-2xl text-white font-medium">История</p>
          </div>
        </div>
        <div class="flex items-center space-x-6">
          @svg('tabler-stairs-up', 'w-9 h-9 text-gray-200')
          <div>
            <p class="text-base text-gray-200">ТИУ в рейтингах</p>
            <p class="text-2xl text-white font-medium">Рейтинги</p>
          </div>
        </div>
        <div class="flex items-center space-x-6">
          @svg('tabler-news', 'w-9 h-9 text-gray-200')
          <div>
            <p class="text-base text-gray-200">Что пишут о нас в СМИ</p>
            <p class="text-2xl text-white font-medium">СМИ</p>
          </div>
        </div>
      </div>
    </div>
    <x-base.news/>
    <x-event.widget/>
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
