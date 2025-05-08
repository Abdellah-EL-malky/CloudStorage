@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1>Partages</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="myshares-tab" data-bs-toggle="tab" data-bs-target="#myshares" type="button" role="tab" aria-controls="myshares" aria-selected="true">Mes partages</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="sharedwithme-tab" data-bs-toggle="tab" data-bs-target="#sharedwithme" type="button" role="tab" aria-controls="sharedwithme" aria-selected="false">Partagés avec moi</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="myshares" role="tabpanel" aria-labelledby="myshares-tab">
                        @if(isset($myShares) && $myShares->count() > 0)
                            <div class="list-group">
                                @foreach($myShares as $share)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if($share->shareable_type == 'App\Models\File')
                                                    <i class="bi bi-file me-2"></i>
                                                @else
                                                    <i class="bi bi-folder me-2"></i>
                                                @endif
                                                {{ $share->shareable->name ?? 'Élément supprimé' }}

                                                <small class="ms-2 text-muted">
                                                    @if($share->recipient_id)
                                                        Partagé avec {{ $share->recipient->name }}
                                                    @else
                                                        Partagé publiquement
                                                    @endif
                                                </small>
                                            </div>

                                            <div>
                                                <span class="badge bg-info me-2">{{ $share->permission }}</span>

                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ $share->getShareUrl() }}" target="_blank">
                                                                <i class="bi bi-link"></i> Ouvrir le lien
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item" type="button" onclick="copyToClipboard('{{ $share->getShareUrl() }}')">
                                                                <i class="bi bi-clipboard"></i> Copier le lien
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <form id="delete-share-{{ $share->id }}" action="{{ route('shares.destroy', $share) }}" method="POST" class="d-none">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); if(confirm('Supprimer ce partage?')) document.getElementById('delete-share-{{ $share->id }}').submit();">
                                                                <i class="bi bi-trash"></i> Supprimer
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                Vous n'avez pas encore partagé de fichiers ou de dossiers.
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="sharedwithme" role="tabpanel" aria-labelledby="sharedwithme-tab">
                        @if(isset($sharedWithMe) && $sharedWithMe->count() > 0)
                            <div class="list-group">
                                @foreach($sharedWithMe as $share)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if($share->shareable_type == 'App\Models\File')
                                                    <i class="bi bi-file me-2"></i>
                                                @else
                                                    <i class="bi bi-folder me-2"></i>
                                                @endif
                                                {{ $share->shareable->name ?? 'Élément supprimé' }}

                                                <small class="ms-2 text-muted">
                                                    Partagé par {{ $share->user->name }}
                                                </small>
                                            </div>

                                            <div>
                                                <span class="badge bg-info me-2">{{ $share->permission }}</span>

                                                <a href="{{ $share->getShareUrl() }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="bi bi-eye"></i> Voir
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                Aucun fichier ou dossier n'a été partagé avec vous.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
