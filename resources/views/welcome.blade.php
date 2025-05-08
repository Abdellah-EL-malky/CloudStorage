@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-6">
                <h1 class="display-5 fw-bold mb-4">Stockage cloud simple et sécurisé</h1>
                <p class="lead mb-4">Gérez, partagez et collaborez sur vos fichiers en toute sécurité. Solution cloud adaptée à tous vos besoins.</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 me-md-2">Commencer gratuitement</a>
                    <a href="#features" class="btn btn-outline-secondary btn-lg px-4">En savoir plus</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/cloudbg.png') }}" class="img-fluid" alt="CloudStorage">
            </div>
        </div>

        <div id="features" class="py-5">
            <h2 class="text-center mb-5">Fonctionnalités principales</h2>

            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card h-min border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-4">
                                <x-heroicon-o-lock-closed class="" style="width:50px; height:50px;"/>
                            </div>
                            <h5 class="card-title">Stockage sécurisé</h5>
                            <p class="card-text">Vos fichiers sont cryptés et protégés avec les dernières technologies de sécurité.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-min border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-4">
                                <x-heroicon-o-users class="" style="width:50px; height:50px;" />
                            </div>
                            <h5 class="card-title">Partage facile</h5>
                            <p class="card-text">Partagez vos fichiers en quelques clics avec qui vous voulez.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-4">
                                <x-heroicon-o-arrow-up-tray class=""  style="width:50px; height:50px;"/>
                            </div>
                            <h5 class="card-title">Collaboration</h5>
                            <p class="card-text">Travaillez en équipe sur vos documents en temps réel.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="pricing" class="py-5">
            <h2 class="text-center mb-5">Nos offres</h2>

            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card h-100 pricing-card">
                        <div class="card-header bg-white border-0 text-center pt-4">
                            <h5 class="card-title">Gratuit</h5>
                            <div class="price">
                                <span class="amount">0€</span>
                                <span class="period">/mois</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> 1GB Stockage</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Fichiers jusqu'à 100MB</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Partage basique</li>
                            </ul>
                        </div>
                        <div class="card-footer bg-white border-0 text-center pb-4">
                            <a href="{{ route('register') }}" class="btn btn-primary w-100">Choisir ce plan</a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100 pricing-card border-primary">
                        <div class="card-header bg-white border-0 text-center pt-4">
                            <span class="badge bg-primary mb-2">Populaire</span>
                            <h5 class="card-title">Premium</h5>
                            <div class="price">
                                <span class="amount">9.99€</span>
                                <span class="period">/mois</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> 5GB Stockage</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Fichiers jusqu'à 250MB</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Collaboration en temps réel</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Support prioritaire</li>
                            </ul>
                        </div>
                        <div class="card-footer bg-white border-0 text-center pb-4">
                            <a href="{{ route('register') }}" class="btn btn-primary w-100">Choisir ce plan</a>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100 pricing-card">
                        <div class="card-header bg-white border-0 text-center pt-4">
                            <h5 class="card-title">Business</h5>
                            <div class="price">
                                <span class="amount">19.99€</span>
                                <span class="period">/mois</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> 10GB Stockage</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Fichiers jusqu'à 500MB</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Outils administratifs avancés</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Accès API</li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Support 24/7</li>
                            </ul>
                        </div>
                        <div class="card-footer bg-white border-0 text-center pb-4">
                            <a href="{{ route('register') }}" class="btn btn-primary w-100">Choisir ce plan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="contact" class="py-5">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="mb-4">Contactez-nous</h2>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="contact-info mt-5 mt-lg-0">
                        <h3 class="mb-4">CloudStorage</h3>
                        <p class="text-muted">Solution de stockage cloud simple et sécurisée pour tous.</p>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-envelope text-primary me-3 fs-4"></i>
                            <span>contact@cloudstorage.example</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-telephone text-primary me-3 fs-4"></i>
                            <span>+212 1 23 45 67 89</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-geo-alt text-primary me-3 fs-4"></i>
                            <span>123 Avenue Cloud, 75000 Paris, France</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>CloudStorage</h5>
                    <p class="text-muted">Solution de stockage cloud simple et sécurisée pour tous.</p>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6>Produit</h6>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-decoration-none text-muted">Fonctionnalités</a></li>
                        <li><a href="#pricing" class="text-decoration-none text-muted">Tarifs</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Sécurité</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6>Entreprise</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none text-muted">À propos</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Blog</a></li>
                        <li><a href="#contact" class="text-decoration-none text-muted">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Légal</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none text-muted">Confidentialité</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">CGU</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Mentions légales</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} CloudStorage</p>
            </div>
        </div>
    </footer>
@endsection
