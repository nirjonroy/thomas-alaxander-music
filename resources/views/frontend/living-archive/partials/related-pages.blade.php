@if ($relatedPages->isNotEmpty())
    <section class="archive-related" aria-labelledby="archive-related-title">
        <div class="archive-section-heading">
            <span class="archive-eyebrow">Related Archive</span>
            <h2 id="archive-related-title">Related Pages</h2>
        </div>
        <div class="archive-related__grid">
            @foreach ($relatedPages as $entry)
                @include('frontend.living-archive.partials.archive-card', ['entry' => $entry, 'resolver' => $resolver])
            @endforeach
        </div>
    </section>
@endif
