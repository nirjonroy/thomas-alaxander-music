<nav class="archive-record-nav" aria-label="Archive record navigation">
    <div>
        @if ($previousPage)
            <span>Previous</span>
            <a href="{{ route('front.living-archive.show', ['path' => $resolver->pathFor($previousPage)]) }}" aria-label="Previous archive page: {{ $previousPage->title }}">{{ $previousPage->title }}</a>
        @endif
    </div>
    <div>
        @if ($nextPage)
            <span>Next</span>
            <a href="{{ route('front.living-archive.show', ['path' => $resolver->pathFor($nextPage)]) }}" aria-label="Next archive page: {{ $nextPage->title }}">{{ $nextPage->title }}</a>
        @endif
    </div>
</nav>
