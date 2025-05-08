@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-white text-center py-4">
                        <div class="d-flex justify-content-center">
                            <div class="steps">
                                <div class="step completed">
                                    <span class="step-circle"><i class="bi bi-check"></i></span>
                                    <span class="step-text">Compte</span>
                                </div>
                                <div class="step active">
                                    <span class="step-circle">2</span>
                                    <span class="step-text">Choisir un plan</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <h4 class="mb-4 text-center">Choisissez votre plan</h4>

                        <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
                            <div class="col">
                                <div class="card h-100 plan-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Gratuit</h5>
                                        <div class="price">
                                            <span class="amount">0€</span>
                                            <span class="period">/mois</span>
                                        </div>
                                        <ul class="plan-features">
                                            <li><i class="bi bi-check"></i> 1GB Stockage</li>
                                            <li><i class="bi bi-check"></i> Fichiers jusqu'à 100MB</li>
                                            <li><i class="bi bi-check"></i> Support basique</li>
                                        </ul>
                                        <form action="{{ route('plans.select') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="plan" value="free">
                                            <button type="submit" class="btn btn-primary w-100">Choisir ce plan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card h-100 plan-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Premium</h5>
                                        <div class="price">
                                            <span class="amount">9.99€</span>
                                            <span class="period">/mois</span>
                                        </div>
                                        <ul class="plan-features">
                                            <li><i class="bi bi-check"></i> 5GB Stockage</li>
                                            <li><i class="bi bi-check"></i> Fichiers jusqu'à 250MB</li>
                                            <li><i class="bi bi-check"></i> Support prioritaire</li>
                                            <li><i class="bi bi-check"></i> Partage avancé</li>
                                        </ul>
                                        <form action="{{ route('plans.select') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="plan" value="premium">
                                            <button type="submit" class="btn btn-primary w-100">Choisir ce plan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card h-100 plan-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Business</h5>
                                        <div class="price">
                                            <span class="amount">19.99€</span>
                                            <span class="period">/mois</span>
                                        </div>
                                        <ul class="plan-features">
                                            <li><i class="bi bi-check"></i> 10GB Stockage</li>
                                            <li><i class="bi bi-check"></i> Fichiers jusqu'à 500MB</li>
                                            <li><i class="bi bi-check"></i> Support 24/7</li>
                                            <li><i class="bi bi-check"></i> Collaboration d'équipe</li>
                                            <li><i class="bi bi-check"></i> Contrôles administratifs</li>
                                        </ul>
                                        <form action="{{ route('plans.select') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="plan" value="business">
                                            <button type="submit" class="btn btn-primary w-100">Choisir ce plan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <form action="{{ route('plans.select') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan" value="free">
                                <button type="submit" class="btn btn-link text-decoration-none">
                                    Continuer avec le plan gratuit pour l'instant
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
