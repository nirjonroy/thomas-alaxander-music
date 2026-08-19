<nav class="archive-breadcrumbs" aria-label="Breadcrumb">
    <a href="{{ route('front.home.living-archive') }}">Living Archive</a>
    @foreach ($ancestors as $ancestor)
        <span aria-hidden="true">/</span>
        <a href="{{ route('front.living-archive.show', ['path' => $resolver->pathFor($ancestor)]) }}">{{ $ancestor->title }}</a>
    @endforeach
    <span aria-hidden="true">/</span>
    <span aria-current="page">{{ $archivePage->title }}</span>
</nav>
