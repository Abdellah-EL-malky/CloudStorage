@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-4">
                <h1>Tableau de bord</h1>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Utilisation du stockage</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ Auth::user()->formatted_storage_used }} utilisés</span>
                            <span>{{ Auth::user()->formatted_storage_limit }}</span>
                        </div>
                        <div class="progress" style="height: 15px;">
                            <div class="progress-bar" style="width: {{ $storagePercentage }}%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Actions rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('files.index') }}" class="btn btn-primary">
                                <i class="bi bi-folder"></i> Mes fichiers
                            </a>
                            <a href="{{ route('shares.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-share"></i> Partages
                            </a>
                            <a href="{{ route('plans') }}" class="btn btn-outline-success">
                                <i class="bi bi-arrow-up-circle"></i> Mettre à niveau mon plan
                            </a>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                <i class="bi bi-upload"></i> Importer un fichier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Fichiers récents</h5>
                        <a href="{{ route('files.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                    </div>
                    <div class="card-body">
                        @if($recentFiles->count() > 0)
                            <div class="list-group">
                                @foreach($recentFiles as $file)
                                    <a href="{{ route('files.show', $file) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="bi {{ $file->getIconClass() }}"></i>
                                            {{ $file->name }}
                                        </div>
                                        <span class="badge bg-primary rounded-pill">{{ $file->formatted_size }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Aucun fichier récent.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Importer un fichier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Sélectionner un fichier</label>
                            <input class="form-control" type="file" id="file" name="file" required>
                            <small class="form-text text-muted">Taille maximale: 100 Mo</small>
                        </div>
                        <div class="mb-3">
                            <label for="folder_id" class="form-label">Dossier (optionnel)</label>
                            <select class="form-select" id="folder_id" name="folder_id">
                                <option value="">Racine</option>
                                @foreach(Auth::user()->folders as $folder)
                                    <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Importer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
