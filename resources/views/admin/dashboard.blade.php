@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12 mt-5">
                <h1>Administration - Tableau de bord</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Utilisateurs totaux</h5>
                        <div class="d-flex align-items-center">
                            <h2 class="mb-0">{{ $totalUsers }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Utilisateurs actifs</h5>
                        <div class="d-flex align-items-center">
                            <h2 class="mb-0">{{ $activeUsers }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Stockage total</h5>
                        <div class="d-flex align-items-center">
                            @php
                                $bytes = $totalStorage;
                                if ($bytes >= 1073741824) {
                                    $size = number_format($bytes / 1073741824, 2) . ' GB';
                                } elseif ($bytes >= 1048576) {
                                    $size = number_format($bytes / 1048576, 2) . ' MB';
                                } else {
                                    $size = number_format($bytes / 1024, 2) . ' KB';
                                }
                            @endphp
                            <h2 class="mb-0">{{ $size }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Revenu mensuel</h5>
                        <div class="d-flex align-items-center">
                            <h2 class="mb-0">{{ number_format($monthlyRevenue, 2) }} €</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Distribution des plans</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Plan</th>
                                <th>Utilisateurs</th>
                                <th>Pourcentage</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($planDistribution as $plan)
                                <tr>
                                    <td>{{ ucfirst($plan->account_type) }}</td>
                                    <td>{{ $plan->total }}</td>
                                    <td>{{ round(($plan->total / $totalUsers) * 100, 2) }}%</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Actions rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                                <i class="bi bi-people"></i> Gérer les utilisateurs
                            </a>
                            <a href="{{ route('admin.activity') }}" class="btn btn-outline-primary">
                                <i class="bi bi-activity"></i> Voir l'activité récente
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
