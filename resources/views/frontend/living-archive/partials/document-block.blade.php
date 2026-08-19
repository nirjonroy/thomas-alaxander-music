@php
    $documentUrl = str_starts_with($archivePage->document_image, 'http')
        ? $archivePage->document_image
        : asset($archivePage->document_image);
    $documentAlt = $archivePage->document_image_alt ?: ($archivePage->document_caption ?: $archivePage->title . ' historical document');
@endphp

<figure class="archive-document">
    <button class="archive-document__button" type="button" data-archive-dialog-open="archive-document-dialog">
        <img src="{{ $documentUrl }}" alt="{{ $documentAlt }}" loading="lazy">
        <span>Open document larger</span>
    </button>
    @if ($archivePage->document_caption)
        <figcaption>{{ $archivePage->document_caption }}</figcaption>
    @endif
</figure>

<dialog class="archive-dialog" id="archive-document-dialog" aria-label="{{ $documentAlt }}">
    <div class="archive-dialog__surface">
        <button class="archive-dialog__close" type="button" data-archive-dialog-close aria-label="Close document view">Close</button>
        <img src="{{ $documentUrl }}" alt="{{ $documentAlt }}">
        @if ($archivePage->document_caption)
            <p>{{ $archivePage->document_caption }}</p>
        @endif
    </div>
</dialog>
