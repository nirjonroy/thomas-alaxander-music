@php
    $stateClass = match ($readiness['publication_state']) {
        'Published' => 'success',
        'Ready for Publication' => 'info',
        'Content Complete but Inactive' => 'warning',
        default => 'secondary',
    };
@endphp

<div class="card mb-4 border-{{ $stateClass }}">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
        <h4 class="mb-0">Content Readiness</h4>
        <span class="badge badge-{{ $stateClass }} p-2">{{ $readiness['publication_state'] }}</span>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-6 col-md-3 mb-3"><strong>{{ $readiness['artworks_complete_count'] }}/{{ $readiness['total_companions'] }}</strong><br><small class="text-muted">Artwork</small></div>
            <div class="col-6 col-md-3 mb-3"><strong>{{ $readiness['teachings_complete_count'] }}/{{ $readiness['total_companions'] }}</strong><br><small class="text-muted">Expanded Teachings</small></div>
            <div class="col-6 col-md-3 mb-3"><strong>{{ $readiness['remembrance_complete_count'] }}/{{ $readiness['total_companions'] }}</strong><br><small class="text-muted">Remembrance Passages</small></div>
            <div class="col-6 col-md-3 mb-3"><strong>{{ $readiness['branding_complete_count'] }}/{{ $readiness['total_companions'] }}</strong><br><small class="text-muted">Branding Blocks</small></div>
            <div class="col-6 col-md-3"><strong>{{ $readiness['crests_complete_count'] }}/2</strong><br><small class="text-muted">Ceremonial Crests</small></div>
            <div class="col-6 col-md-3"><strong>{{ $readiness['total_companions'] - $readiness['inactive_companions_count'] }}/{{ $readiness['total_companions'] }}</strong><br><small class="text-muted">Active Companions</small></div>
            <div class="col-6 col-md-3"><strong>{{ $readiness['narrative_complete'] ? 'Complete' : 'Incomplete' }}</strong><br><small class="text-muted">Page Narrative</small></div>
            <div class="col-6 col-md-3"><strong>{{ $readiness['missing_item_count'] }}</strong><br><small class="text-muted">Publication Tasks</small></div>
        </div>

        @if ($readiness['missing_item_count'])
            <div class="border-top pt-3 mt-3">
                <p class="mb-2 font-weight-bold">Complete these items before publishing:</p>
                <ul class="mb-0 pl-3">
                    @foreach ($readiness['missing_items'] as $item)
                        <li class="mb-1">
                            {{ !empty($item['sequence']) ? sprintf('%02d. ', $item['sequence']) : '' }}{{ $item['name'] }}: {{ $item['issue'] }}
                            @if (!empty($item['companion_id']))
                                <a class="ml-1" href="{{ route('admin.companions-journey.edit', $item['companion_id']) }}">Edit {{ $item['name'] }}</a>
                            @else
                                <a class="ml-1" href="{{ route('admin.companions-journey.settings') }}">Open Page Settings</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <p class="mb-0 text-success"><i class="fas fa-check-circle"></i> All required content has been entered. Activate the page and each companion when approved for public release.</p>
        @endif
    </div>
</div>
