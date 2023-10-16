<div class="bg-gradient-to-r from-sky-500 to-indigo-700 py-8" x-bind:class="statusVisualImpaired && infoVisualImpaired.typeColor ? '!bg-none !bg-gray-700' : ''">
  <div class="container mx-auto space-y-9">
    <div class="grid grid-cols-3 gap-9">
      <div>
        <a href="/" class="flex">
          <img src="/media/images/{{ config('settings.logo') ?? '' }}" style="width: 252px;">
        </a>
      </div>
      <div class="space-y-5">
        <p class="text-2xl text-white font-medium">ТИУ в социальных сетях</p>
        <x-base.social/>
      </div>
      <div>
        <p x-on:click="scrollTop(), openVisualImpaired()" class="text-2xl text-white font-medium flex mb-5 cursor-pointer">
          @svg('tabler-eye', 'w-6 h-6 mt-1 mr-5 text-white')
          Версия для слабовидящих
        </p>
        <a href="" class="text-base text-gray-200 hover:underline">Карта сайта</a>
      </div>
    </div>
    <div class="grid grid-cols-3 gap-9">
      <div></div>
      <div class="space-y-5">
        <p class="text-2xl text-white font-medium">Контакты</p>
        <div class="text-base text-gray-200 space-y-1">
          <p>{{ json_decode(config('settings.contacts'))->location ?? '' }}</p>
          <p>Телефон/факс: <a href="tel:{{ json_decode(config('settings.contacts'))->phone ?? '' }}" class="text-base text-gray-200 hover:underline">{{ json_decode(config('settings.contacts'))->phone ?? '' }}</a></p>
          <p>Электронная почта: <a href="mailto:{{ json_decode(config('settings.contacts'))->mail ?? '' }}" class="text-base text-gray-200 hover:underline">{{ json_decode(config('settings.contacts'))->mail ?? '' }}</a></p>
        </div>
      </div>
      <div class="space-y-5">
        <p class="text-2xl text-white font-medium">Приемная комиссия</p>
        <div class="text-base text-gray-200 space-y-1">
          <p>{{ json_decode(config('settings.acceptance_contacts'))->location ?? '' }}</p>
          <p>Телефон/факс: <a href="tel:{{ json_decode(config('settings.acceptance_contacts'))->phone ?? '' }}" class="text-base text-gray-200 hover:underline">{{ json_decode(config('settings.acceptance_contacts'))->phone ?? '' }}</a></p>
          <p>Электронная почта: <a href="mailto:{{ json_decode(config('settings.acceptance_contacts'))->mail ?? '' }}" class="text-base text-gray-200 hover:underline">{{ json_decode(config('settings.acceptance_contacts'))->mail ?? '' }}</a></p>
        </div>
        <div class="text-base text-gray-200 space-y-1">
          <p>Часы работы:</p>
          @foreach(json_decode(config('settings.acceptance_contacts'))->opening_hours ?? [] as $hour)
            <p>{{ $hour }}</p>
          @endforeach
        </div>
      </div>
    </div>
    <hr>
    <div class="flex justify-between">
      <p class="text-white text-lg">© 2000-{{ date('Y') }}, {{ config('settings.app_name') ?? '' }}</p>
      <a href="https://digital.tyuiu.ru" target="_blank" class="flex items-center text-base text-gray-200/70 hover:underline">
        @svg('tabler-atom', 'w-5 h-5 flex-none')
        Разработано Департаментом цифрового развития ТИУ
      </a>
    </div>
  </div>
</div>
<div class="bg-gray-800">
  <div class="container mx-auto h-28 text-white flex justify-between">
    <div class="h-28 flex items-center space-x-8">
      <p class="text-xl font-extrabold">{{ $_SERVER['HTTP_HOST'] }}</p>
      <p class="text-base">Минобрнауки России</p>
    </div>
    <img src="/images/settings/minobr.png">
  </div>
</div>

<x-base.preloader/>
