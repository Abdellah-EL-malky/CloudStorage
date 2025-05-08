@extends('layouts.app')

@section('content')
    <div class="container">
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
                                <div class="step completed">
                                    <span class="step-circle"><i class="bi bi-check"></i></span>
                                    <span class="step-text">Choisir un plan</span>
                                </div>
                                <div class="step active">
                                    <span class="step-circle">3</span>
                                    <span class="step-text">Paiement</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <h4 class="mb-4 text-center">Finaliser votre achat</h4>

                        <div class="mb-4 p-3 border rounded">
                            <h5>Récapitulatif</h5>
                            <p><strong>Plan choisi :</strong> {{ $planModel->type }}</p>
                            <p><strong>Prix :</strong> {{ $planModel->price }}€ / mois</p>
                            <p><strong>Stockage :</strong> {{ number_format($planModel->storage / 1073741824, 1) }} GB</p>
                        </div>

                        <form action="{{ route('payment.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $planModel->id }}">
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="bi bi-paypal me-2"></i> Payer avec PayPal
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('plans') }}" class="btn btn-link text-decoration-none">
                                <i class="bi bi-arrow-left"></i> Revenir au choix des plans
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
