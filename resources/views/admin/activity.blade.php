@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12 mt-5">
                <h1>Administration - Activité récente</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Uploads récents</h5>
                    </div>
                    <div class="card-body">
                        @if($recentUploads->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($recentUploads as $file)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="bi {{ $file->isImage() ? 'bi-file-image' : ($file->isPdf() ? 'bi-file-pdf' : 'bi-file') }} me-2"></i>
                                            {{ $file->name }}
                                            <span class="text-muted ms-2">par {{ $file->user->name }}</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary">{{ $file->formatted_size }}</span>
                                            <small class="text-muted ms-2">{{ $file->created_at->diffForHumans() }}</small>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted text-center py-3">Aucun upload récent.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Partages récents</h5>
                    </div>
                    <div class="card-body">
                        @if($recentShares->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($recentShares as $share)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            @if($share->shareable_type == 'App\Models\File')
                                                <i class="bi bi-file me-2"></i>
                                                {{ $share->shareable->name ?? 'Fichier' }}
                                            @else
                                                <i class="bi bi-folder me-2"></i>
                                                {{ $share->shareable->name ?? 'Fichier' }}
                                            @endif
                                            <span class="text-muted ms-2">par {{ $share->user->name }}</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-info">{{ $share->permission }}</span>
                                            <small class="text-muted ms-2">{{ $share->created_at->diffForHumans() }}</small>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted text-center py-3">Aucun partage récent.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
