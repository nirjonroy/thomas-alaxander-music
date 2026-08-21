<nav class="epk-nav" aria-label="EPK navigation">
    @foreach($epkPages as $page)
        <a href="{{ $page->publicUrl() }}" @if($currentPage->id === $page->id) aria-current="page" @endif>
            {{ $page->slug === 'full-artist' ? 'Full Artist' : ($page->slug === 'crooners' ? 'Crooners' : $page->title) }}
        </a>
    @endforeach
</nav>
