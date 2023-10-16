<div class="text-2xl font-medium flex flex-wrap gap-x-9 gap-y-4">
    @foreach($articles as $article)
        <a href="{{ $article->route }}" class="whitespace-nowrap {{ url()->current() == $article->route ? 'text-blue-800 border-b-2 border-blue-800' : '' }}">{{ $article->title }}</a>
    @endforeach
</div>
