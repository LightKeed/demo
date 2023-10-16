<!-- Сделать проверку enabled_menu )) -->
<div>
  <x-base.header.visually-impaired/>
  <x-base.header.quicklinks/>
  <x-base.header.navigation :categories="$categories"/>
  <x-base.header.menu :categories="$categories"/>
  <x-base.header.search/>
  <div
    class="absolute w-full top-0 bottom-0 z-[45] backdrop-blur-md bg-gray-800/30 transition-opacity duration-[400ms] ease-in-out"
    x-on:click="closeFull()"
    x-bind:class="statusMenu || statusSearch ? 'opacity-100 h-full' : 'opacity-0 h-0'"></div>
</div>
