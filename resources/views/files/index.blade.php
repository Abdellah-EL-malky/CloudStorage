@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12 mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Mes fichiers</h1>
                    <div>
                        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="bi bi-upload"></i> Importer
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                            <i class="bi bi-folder-plus"></i> Nouveau dossier
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Navigation</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group mb-3">
                            <a href="{{ route('files.index') }}" class="list-group-item list-group-item-action {{ !request('folder_id') && !request('favorites') && !request('tag_id') ? 'active' : '' }}">
                                <i class="bi bi-hdd"></i> Tous les fichiers
                            </a>
                        </div>

                        <h6 class="mb-2">Dossiers</h6>
                        <div class="list-group mb-3">
                            @foreach(Auth::user()->folders()->whereNull('parent_id')->orderBy('name')->get() as $folder)
                                <a href="{{ route('files.index', ['folder_id' => $folder->id]) }}"
                                   class="list-group-item list-group-item-action {{ request('folder_id') == $folder->id ? 'active' : '' }}">
                                    <i class="bi bi-folder"></i> {{ $folder->name }}
                                </a>
                            @endforeach
                        </div>

                        @if(isset($tags) && $tags->count() > 0)
                            <h6 class="mb-2">Tags</h6>
                            <div class="list-group">
                                @foreach($tags as $tag)
                                    <a href="{{ route('files.index', ['tag_id' => $tag->id]) }}"
                                       class="list-group-item list-group-item-action {{ request('tag_id') == $tag->id ? 'active' : '' }}">
                                        <i class="bi bi-tag" style="color: {{ $tag->color }};"></i>
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <nav>
                                <ol class="breadcrumb mb-0">
                                    @if(isset($currentFolder))
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('files.index') }}">Racine</a>
                                        </li>
                                        <li class="breadcrumb-item active">{{ $currentFolder->name }}</li>
                                    @endif
                                </ol>
                            </nav>

                            <div>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-sort-down"></i> Trier
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_dir' => 'asc']) }}">
                                                Nom (A-Z)
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_dir' => 'desc']) }}">
                                                Plus récent
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'size', 'sort_dir' => 'desc']) }}">
                                                Plus grand
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6 offset-md-3">
                                <form action="{{ route('files.index') }}" method="GET">
                                    @if(request('folder_id'))
                                        <input type="hidden" name="folder_id" value="{{ request('folder_id') }}">
                                    @endif
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @if(isset($folders) && $folders->count() > 0)
                            <h5 class="mb-3">Dossiers</h5>
                            <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
                                @foreach($folders as $folder)
                                    <div class="col">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h5>
                                                        <a href="{{ route('files.index', ['folder_id' => $folder->id]) }}" class="text-decoration-none">
                                                            <i class="bi bi-folder text-warning"></i> {{ $folder->name }}
                                                        </a>
                                                    </h5>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="showShareModal('folder', {{ $folder->id }})">
                                                                    <i class="bi bi-share"></i> Partager
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form id="delete-folder-{{ $folder->id }}" action="{{ route('folders.destroy', $folder) }}" method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                                <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer ce dossier?')) document.getElementById('delete-folder-{{ $folder->id }}').submit();">
                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <h5 class="mb-3">Fichiers</h5>
                        @if(isset($files) && $files->count() > 0)
                            <div class="row row-cols-1 row-cols-md-3 g-4">
                                @foreach($files as $file)
                                    <div class="col">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h5>
                                                        <i class="bi {{ $file->getIconClass() }} {{ $file->getIconColor() }}"></i>
                                                        {{ Str::limit($file->name, 20) }}
                                                    </h5>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('files.download', $file) }}">
                                                                    <i class="bi bi-download"></i> Télécharger
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="showShareModal('file', {{ $file->id }})">
                                                                    <i class="bi bi-share"></i> Partager
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form id="delete-file-{{ $file->id }}" action="{{ route('files.destroy', $file) }}" method="POST" style="display: none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                                <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer ce fichier?')) document.getElementById('delete-file-{{ $file->id }}').submit();">
                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                @if($file->isImage())
                                                    <div class="text-center mb-2">
                                                        <img src="{{ Storage::url($file->path) }}" class="img-thumbnail" alt="{{ $file->name }}" style="max-height: 100px;">
                                                    </div>
                                                @endif

                                                <p class="text-muted">
                                                    {{ $file->formatted_size }} - {{ $file->created_at->format('d/m/Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                {{ $files->links() }}
                            </div>
                        @else
                            <div class="alert alert-info">
                                Aucun fichier à afficher.
                            </div>
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
                    @if(isset($currentFolder))
                        <input type="hidden" name="folder_id" value="{{ $currentFolder->id }}">
                    @endif
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Sélectionner un fichier</label>
                            <input class="form-control" type="file" id="file" name="file" required>
                            <small class="form-text text-muted">Taille maximale: 100 Mo</small>
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

    <div class="modal fade" id="createFolderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Nouveau dossier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('folders.store') }}" method="POST">
                    @csrf
                    @if(isset($currentFolder))
                        <input type="hidden" name="parent_id" value="{{ $currentFolder->id }}">
                    @endif
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du dossier</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="shareModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="shareModalTitle">Partager</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="shareForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="share_type" class="form-label">Type de partage</label>
                            <select class="form-select" id="share_type" name="share_type" required onchange="toggleEmailField()">
                                <option value="link">Lien public</option>
                                <option value="user">Utilisateur spécifique</option>
                            </select>
                        </div>

                        <div class="mb-3" id="emailFieldContainer" style="display: none;">
                            <label for="email" class="form-label">Email du destinataire</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <div class="mb-3">
                            <label for="permission" class="form-label">Type d'accès</label>
                            <select class="form-select" id="permission" name="permission" required>
                                <option value="read" selected>Lecture seule</option>
                                <option value="write">Lecture et écriture</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Date d'expiration (optionnel)</label>
                            <input type="date" class="form-control" id="expires_at" name="expires_at">
                        </div>

                        <div id="shareUrlContainer" class="alert alert-success" style="display: none;">
                            <p>Lien de partage:</p>
                            <div class="input-group mb-3">
                                <input type="text" id="shareUrl" class="form-control" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyShareUrl()">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary" id="shareButton">
                            Partager
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
