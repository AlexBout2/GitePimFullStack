<!-- resources/views/kayak/create.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gîte Pim - Kayak - Réservation</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdn.bootstrap.com">

    <meta name="description" content="Réservez un kayak pour visiter le lagon du gîte de Pim">
    <meta name="author" content="Gîte Pim">
    <meta name="robots" content="index, follow">
    <meta name="keywords" content="gîte, Poum, Nouvelle-Calédonie, kayak, lagon, location">
    <meta name="geo.region" content="NC">
    <meta name="geo.placename" content="Poum">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:title" content="Gîte Pim - Location de kayak à Poum">
    <meta property="og:description" content="Réservez un kayak pour explorer le lagon">
    <meta property="og:image" content="https://gitedepim.netlify.app/public/logo-gite-BLANC.webp">
    <meta property="og:url" content="{{ route('kayak.create') }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="Gîte Pim">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/front.css', 'resources/js/front.js', 'resources/css/modules/kayak.css', 'resources/js/forms/kayak-form.js'])

    <link rel="canonical" href="{{ route('kayak.create') }}">
</head>

<body>
    <x-navbar />
    <main>
        <!-- Hero Section -->
        <section class="hero-resa">
            <div class="hero-container-resa p-3">
                <div class="hero-text-box glass-effect col-md-6 col-12 m-auto">
                    <h1 class="mb-5 text-center text-shadow text-white">Pour réserver un kayak, c'est ici !</h1>
                </div>
            </div>
        </section>

        <section class="formulaire">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-11 mx-auto reservation-form mt-4">
                        <!-- Numéro de séjour -->
                        <div class="form-group mb-3">
                            <input type="text" id="sejour-number" class="form-control sejour-resa-input"
                                placeholder="Votre numéro de séjour" required>
                            <div class="invalid-feedback">Veuillez entrer un numéro de séjour valide.</div>
                        </div>

                        <div class="d-flex justify-content-center mb-3">
                            <button type="button" class="btn-main text-center sejour-validation">
                                Confirmer votre numéro de séjour
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row" id="kayak-form-container" style="display: none;">
                    <form id="kayak-reservation-form" class="col-md-6 col-11 mx-auto reservation-form mt-4"
                        action="{{ route('kayak.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="sejour_number" id="sejour-number-hidden">

                        <!-- Date de location -->
                        <x-date-selector mode="single" label="Sélectionnez la date de location" :startDate="old('date')" />

                        <hr class="my-4" style="opacity: 0.3;">

                        <!-- Heure de début -->
                        <div class="form-group mb-4">
                            <label class="mb-3 d-block text-center fw-bold">Heure de départ (durée 1h)</label>
                            <div class="d-flex justify-content-center">
                                <select class="form-select w-50 @error('heure_debut') is-invalid @enderror"
                                    id="heure_debut" name="heure_debut" required>
                                    <option value="" disabled selected>Choisir...</option>
                                    @for ($hour = 9; $hour <= 15; $hour++)
                                        <option value="{{ $hour }}"
                                            {{ old('heure_debut') == $hour ? 'selected' : '' }}>{{ $hour }}h00
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <input type="hidden" name="duree" value="1">
                            @error('heure_debut')
                                <div class="invalid-feedback d-block text-center">{{ $message }}</div>
                            @enderror
                            <div id="hour-availability-message" class="text-danger mt-2 text-center"></div>
                        </div>

                        <hr class="my-4" style="opacity: 0.3;">

                        <!-- Nombre de personnes -->
                        <x-person-counter :personCount="old('nb_personnes', 1)" :minPersons="1" :maxPersons="8" fieldName="nb_personnes"
                            label="Nombre de personnes" :showLabels="true" :capaciteInfo="'Capacité maximum: 8 personnes au total'" />

                        <hr class="my-4" style="opacity: 0.3;">

                        <!-- Sélection des kayaks -->
                        <div class="form-group mb-4">
                            <label class="mb-3 d-block text-center fw-bold">Choisissez vos kayaks</label>
                            <div class="row">
                                <div class="col-md-6 text-center mb-3">
                                    <label for="nb_kayak_simple" class="form-label">Kayaks simples</label>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-secondary kayak-counter-btn"
                                            data-target="nb_kayak_simple" data-action="decrement">-</button>
                                        <input type="number" class="form-control mx-2 text-center"
                                            style="width: 80px;" id="nb_kayak_simple" name="nb_kayak_simple"
                                            min="0" max="2" value="{{ old('nb_kayak_simple', 0) }}"
                                            readonly>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-secondary kayak-counter-btn"
                                            data-target="nb_kayak_simple" data-action="increment">+</button>
                                    </div>
                                    <p class="form-text mt-2">2 disponibles</p>
                                    <p class="form-text">1 personne par kayak</p>
                                </div>

                                <div class="col-md-6 text-center mb-3">
                                    <label for="nb_kayak_double" class="form-label">Kayaks doubles</label>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-secondary kayak-counter-btn"
                                            data-target="nb_kayak_double" data-action="decrement">-</button>
                                        <input type="number" class="form-control mx-2 text-center"
                                            style="width: 80px;" id="nb_kayak_double" name="nb_kayak_double"
                                            min="0" max="3" value="{{ old('nb_kayak_double', 0) }}"
                                            readonly>
                                        <button type="button"
                                            class="btn btn-sm btn-outline-secondary kayak-counter-btn"
                                            data-target="nb_kayak_double" data-action="increment">+</button>
                                    </div>
                                    <p class="form-text mt-2">3 disponibles</p>
                                    <p class="form-text">2 personnes par kayak</p>
                                </div>
                            </div>

                            <div id="kayak-error" class="text-danger mt-2 text-center"></div>
                        </div>

                        <x-form-errors />

                        <div class="d-flex justify-content-center mb-4">
                            <button type="submit" class="btn-main text-center">
                                Réserver mon kayak
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Confirmation de réservation -->
                @if (session('reservation'))
                    <div class="row">
                        <div class="col-md-6 col-11 mx-auto">
                            <div class="reservation-confirmation p-4 my-3 text-center border rounded">
                                <h2 class="mb-3">Réservation confirmée !</h2>
                                <div class="reservation-details text-start bg-light p-3 rounded mx-auto"
                                    style="max-width: 400px;">
                                    <p><strong>Date :</strong> {{ session('reservation.date') }}</p>
                                    <p><strong>Horaire :</strong> {{ session('reservation.heure_debut') }}h à
                                        {{ session('reservation.heure_fin') }}h</p>
                                    <p><strong>Kayaks :</strong>
                                        @if (session('reservation.kayaks.simple') > 0)
                                            {{ session('reservation.kayaks.simple') }} kayak(s) simple(s)
                                        @endif
                                        @if (session('reservation.kayaks.simple') > 0 && session('reservation.kayaks.double') > 0)
                                            et
                                        @endif
                                        @if (session('reservation.kayaks.double') > 0)
                                            {{ session('reservation.kayaks.double') }} kayak(s) double(s)
                                        @endif
                                    </p>
                                    <p><strong>Personnes :</strong> {{ session('reservation.nb_personnes') }}</p>
                                </div>
                                <p class="mt-3 fs-3 fw-semibold">Conservez votre code de réservation.</p>
                                <div class="row justify-content-center">
                                    <p class="fw-bold col-5 fs-4 p-2">Code de réservation :</p>
                                    <p class="reservation-number fs-4 bg-light p-2 rounded col-5">
                                        {{ session('reservation.code') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
    <x-footer />

    @vite('resources/js/forms/kayak-form.js')
</body>

</html>
