@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12 mt-5">
                <h1>Administration - Gestion des utilisateurs</h1>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Utilisateurs</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <form action="{{ route('admin.users') }}" method="GET" class="d-flex gap-2">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>

                                    <select name="account_type" class="form-select" onchange="this.form.submit()">
                                        <option value="all">Tous les plans</option>
                                        <option value="free" {{ request('account_type') == 'free' ? 'selected' : '' }}>Gratuit</option>
                                        <option value="premium" {{ request('account_type') == 'premium' ? 'selected' : '' }}>Premium</option>
                                        <option value="business" {{ request('account_type') == 'business' ? 'selected' : '' }}>Business</option>
                                    </select>

                                    <select name="status" class="form-select" onchange="this.form.submit()">
                                        <option value="all">Tous les statuts</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                    </select>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Plan</th>
                                    <th>Stockage</th>
                                    <th>Dernière connexion</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ ucfirst($user->name) }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge {{ $user->account_type == 'free' ? 'bg-secondary' : ($user->account_type == 'premium' ? 'bg-primary' : 'bg-success') }}">
                                                {{ ucfirst($user->account_type) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->getFormattedStorageUsedAttribute() }} / {{ $user->getFormattedStorageLimitAttribute() }}</td>
                                        <td>{{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Jamais' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Confirmer la suppression</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong>{{ $user->name }}</strong> ?</p>
                                                            <p class="text-danger">Cette action est irréversible et supprimera tous les fichiers et dossiers associés.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
