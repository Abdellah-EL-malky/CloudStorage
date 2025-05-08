@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-white text-center py-4">
                        <h4 class="mb-0 text-success">
                            <i class="bi bi-check-circle-fill me-2"></i>Paiement réussi!
                        </h4>
                    </div>

                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <div class="success-icon mb-3">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                            </div>

                            <h5 class="mb-3">Merci pour votre achat!</h5>
                            <p class="mb-4">Votre paiement a été traité avec succès et votre nouveau plan est maintenant actif.</p>

                            <div class="alert alert-info">
                                Les détails de la transaction ont été enregistrés et vous recevrez un email de confirmation.
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="bi bi-speedometer me-2"></i>Accéder à mon tableau de bord
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
