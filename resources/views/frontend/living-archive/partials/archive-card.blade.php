<article class="archive-card">
    <span class="archive-card__eyebrow">{{ $entry->section_label ?: 'Archive Entry' }}</span>
    <h3 class="archive-card__title">
        <a href="{{ route('front.living-archive.show', ['path' => $resolver->pathFor($entry)]) }}">{{ $entry->title }}</a>
    </h3>
    @if ($entry->teaser)
        <p class="archive-card__teaser">{{ $entry->teaser }}</p>
    @endif
    <a class="archive-card__cta" href="{{ route('front.living-archive.show', ['path' => $resolver->pathFor($entry)]) }}" aria-label="Open archive record: {{ $entry->title }}">
        Open Record
    </a>
</article>
