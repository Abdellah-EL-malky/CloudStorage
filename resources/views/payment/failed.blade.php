@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-white text-center py-4">
                        <h4 class="mb-0 text-danger">Échec du paiement</h4>
                    </div>

                    <div class="card-body p-4 text-center">
                        <div class="my-4">
                            <div class="mb-4">
                                <i class="bi bi-x-circle text-danger" style="font-size: 5rem;"></i>
                            </div>
                            <h5 class="mb-3">Le paiement n'a pas pu être traité</h5>
                            <p class="text-muted mb-4">
                                Nous n'avons pas pu traiter votre paiement. Veuillez vérifier vos informations de paiement et réessayer, ou choisir une autre méthode de paiement.
                            </p>

                            <div class="d-grid gap-2 col-md-6 mx-auto">
                                <a href="{{ route('plans') }}" class="btn btn-primary">
                                    Retourner à la sélection de plan
                                </a>
                                <a href="{{ route('dashboard') }}" class="btn btn-link text-decoration-none">
                                    Retourner au tableau de bord
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
