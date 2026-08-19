<header class="archive-hero archive-hero--{{ $isCategoryPage ? 'category' : 'article' }}">
    <span class="archive-eyebrow">{{ $archivePage->section_label ?: \Illuminate\Support\Str::headline($archivePage->page_type) }}</span>
    <h1 class="archive-title">{{ $archivePage->title }}</h1>
    @if ($archivePage->teaser)
        <p class="archive-lead">{{ $archivePage->teaser }}</p>
    @endif
</header>
