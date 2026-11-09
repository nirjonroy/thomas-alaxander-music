@extends('admin.master_layout')
@section('title')
<title>Companions Along the Journey</title>
@endsection
@section('admin-content')
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Companions Along the Journey</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">Companions Along the Journey</div>
            </div>
          </div>

          <div class="section-body">
            @include('admin.companions.partials.readiness', ['readiness' => $readiness])

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3" style="gap: 10px;">
                <p class="text-muted mb-0">The 11 canonical Companions along the Sacred Circle. Sequence and identity are immutable.</p>
                <div>
                    <a href="{{ route('admin.companions-journey.preview') }}" target="_blank" class="btn btn-warning mr-2"><i class="fas fa-eye"></i> Preview Page</a>
                    <button type="button" class="btn btn-outline-dark mr-2" id="copyPublicUrlAdminBtn" onclick="copyPublicUrlAdmin()"><i class="fas fa-copy"></i> <span id="copyAdminText">Copy Public URL</span></button>
                    <a href="{{ route('admin.companions-journey.settings') }}" class="btn btn-info"><i class="fas fa-cog"></i> Page Settings</a>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-header">
                        <h4>Canonical Companions</h4>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="dataTable">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">Sequence</th>
                                    <th>Companion</th>
                                    <th>Artwork Status</th>
                                    <th>Teaching Status</th>
                                    <th>Status</th>
                                    <th style="width: 100px;">{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($companions as $companion)
                                    <tr>
                                        <td class="font-weight-bold text-center">
                                            {{ sprintf('%02d', $companion->sequence) }}
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark">{{ $companion->name }}</div>
                                            <div class="small text-muted">{{ $companion->slug }}</div>
                                        </td>
                                        <td>
                                            @if($companion->artwork_image && file_exists(public_path(ltrim($companion->artwork_image, '/\\'))))
                                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Uploaded</span>
                                                <a href="{{ asset($companion->artwork_image) }}" target="_blank" class="ml-2 small text-primary">View</a>
                                            @else
                                                <span class="badge badge-light text-muted">Pending Artwork</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($companion->expanded_teaching && $companion->remember_teaching)
                                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Complete</span>
                                            @elseif($companion->expanded_teaching || $companion->remember_teaching)
                                                <span class="badge badge-warning">Partial</span>
                                            @else
                                                <span class="badge badge-light text-muted">Pending Content</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($companion->is_active)
                                                <span class="badge badge-success">{{__('admin.Active')}}</span>
                                            @else
                                                <span class="badge badge-danger">{{__('admin.Inactive')}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.companions-journey.edit', $companion->id) }}" class="btn btn-primary btn-sm" title="Edit Companion">
                                                <i class="fa fa-edit" aria-hidden="true"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </section>
      </div>
@endsection

@push('js')
<script>
function copyPublicUrlAdmin() {
    var publicUrl = "{{ route('front.living-archive.companions') }}";
    var textSpan = document.getElementById('copyAdminText');
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(publicUrl).then(function() {
            if (textSpan) {
                textSpan.textContent = 'Copied!';
                setTimeout(function() { textSpan.textContent = 'Copy Public URL'; }, 2000);
            }
        }).catch(function() {
            alert("Public URL: " + publicUrl);
        });
    } else {
        alert("Public URL: " + publicUrl);
    }
}
</script>
@endpush
