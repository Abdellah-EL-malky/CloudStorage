@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Paramètres du profil</h5>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ url('/profile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-4">
                                <!-- Avatar -->
                                <div class="col-md-3 text-center">
                                    <div class="mb-3">
                                        @if($user->avatar_path)
                                            <img src="{{ Storage::url($user->avatar_path) }}" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;" alt="Avatar">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 150px; height: 150px; margin: 0 auto;">
                                                <i class="bi bi-person" style="font-size: 5rem; color: #ccc;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="avatar" class="btn btn-sm btn-outline-primary">
                                            Changer la photo
                                        </label>
                                        <input type="file" class="d-none" id="avatar" name="avatar">
                                        @error('avatar')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Informations personnelles -->
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom complet</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Adresse email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}">
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Changement de mot de passe -->
                            <div class="mb-3">
                                <h5>Changer de mot de passe</h5>
                                <p class="text-muted small">Laissez ces champs vides si vous ne souhaitez pas changer votre mot de passe.</p>
                            </div>

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mot de passe actuel</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>

                            <!-- Bouton de sauvegarde -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Informations sur l'abonnement -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Informations sur l'abonnement</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6>Plan actuel : <span class="badge bg-primary">{{ ucfirst($user->account_type) }}</span></h6>
                                <p class="text-muted mb-0">Stockage : {{ $user->getFormattedStorageUsedAttribute() }} / {{ $user->getFormattedStorageLimitAttribute() }}</p>
                            </div>
                            <a href="{{ route('plans') }}" class="btn btn-outline-primary">Changer de plan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
