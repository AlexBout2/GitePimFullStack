<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gîte Pim - Chambre - reservation</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdn.bootstrap.com">

    <meta name="description" content="Réservez une chambre dans notre gîte">
    <meta name="author" content="Gîte Pim">
    <meta name="robots" content="noindex, nofollow">
    <meta name="keywords" content="gîte, Poum, Nouvelle-Calédonie, bungalows">
    <meta name="geo.region" content="NC">
    <meta name="geo.placename" content="Poum">

    <meta property="og:title" content="Gîte Pim - Hébergement et activités à Poum">
    <meta property="og:description" content="réserver une chambre dans notre gîte.">
    <meta property="og:image" content="https://gitedepim.netlify.app/public/logo-gite-BLANC.webp">
    <meta property="og:url" content="https://gitedepim.netlify.app/modules/chambre/chambre-resa.html">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="Gîte Pim">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="canonical" href="{{ route('chambres.create') }}">
</head>

<body>
    <x-navbar />
    <main>
        <!-- Hero Section -->
        <section class="hero-resa">
            <div class="hero-container-resa p-3">
                <div class="hero-text-box glass-effect col-md-6 col-12 m-auto">
                    <h1 class="mb-5 text-center text-shadow text-white">Pour réserver une chambre, c'est ici !</h1>
                </div>
            </div>
        </section>

        <section class="formulaire">
            <div class="container-fluid">
                <div class="row">
                    <form action="{{ route('sejour.store') }}" method="POST"
                        class="col-md-6 col-11 mx-auto reservation-form mt-4">
                        @csrf

                        <x-form-errors :showAll="true" :fields="['startDate', 'endDate', 'typeBungalow', 'bungalowId', 'nbrPersonnes']"
                            title="Veuillez corriger les informations de réservation :" />

                        <!-- Sélection de dates -->
                        <div class="form-group mb-4">
                            <h2 class="mb-5 text-center">À quelle date voulez-vous réserver ?</h2>
                            <div class="row">
                                <div class="col-6">
                                    <label for="startDate">Début du séjour</label>
                                    <input type="date" class="form-control @error('startDate') is-invalid @enderror"
                                        id="startDate" name="startDate" required value="{{ old('startDate') }}"
                                        min="{{ date('Y-m-d') }}">
                                    @error('startDate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="endDate">Fin du séjour</label>
                                    <input type="date" class="form-control @error('endDate') is-invalid @enderror"
                                        id="endDate" name="endDate" required value="{{ old('endDate') }}"
                                        min="{{ date('Y-m-d') }}">
                                    @error('endDate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="opacity: 0.3;">

                          <!-- Sélection du nombre de personnes -->
                        <x-person-counter :personCount="old('nbrPersonnes', 2)" :minPersons="1" :maxPersons="old('typeBungalow') == 'mer' ? 2 : 4"
                            fieldName="nbrPersonnes" label="Nombre de personnes" :capaciteInfo="old('typeBungalow') == 'mer'
                                ? 'Capacité maximale: 2 personnes par bungalow mer'
                                : (old('typeBungalow') == 'jardin'
                                    ? 'Capacité maximale: 4 personnes par bungalow jardin'
                                    : 'Capacité par bungalow')" />
                                    
                        <hr class="my-4" style="opacity: 0.3;">

                        <!-- Sélection du type de bungalow -->
                        <div class="form-group mb-4">
                            <h2 class="mb-5 text-center">Quel type de bungalow voulez-vous réserver ?</h2>

                            <div class="d-flex justify-content-evenly text-center px-5 mt-2">
                                <div class="form-check">
                                    <input type="radio" id="bungalowMer" name="typeBungalow" value="mer"
                                        {{ old('typeBungalow') == 'mer' ? 'checked' : '' }} required>
                                    <label for="bungalowMer">Mer</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="bungalowJardin" name="typeBungalow" value="jardin"
                                        {{ old('typeBungalow') == 'jardin' ? 'checked' : '' }} required>
                                    <label for="bungalowJardin">Jardin</label>
                                </div>
                            </div>

                            @error('typeBungalow')
                                <div class="text-danger text-center">{{ $message }}</div>
                            @enderror

                            <!-- Bungalows Mer -->
                            <div id="bungalowMerContainer"
                                class="{{ old('typeBungalow') == 'mer' ? '' : 'd-none' }} mt-4">
                                <h3 class="mb-3 text-center">Choisissez votre bungalow côté mer</h3>
                                <div class="form-group d-flex justify-content-center flex-column align-items-center">
                                    <select name="bungalowId" id="bungalowMerSelect" class="form-select w-50 mb-3"
                                        {{ old('typeBungalow') != 'mer' ? 'disabled' : '' }}>
                                        <option value="">Sélectionnez un bungalow</option>
                                        @foreach ($bungalowsMer as $bungalow)
                                            @php
                                                $isDisabled = false;
                                                if (old('startDate') && old('endDate')) {
                                                    $isDisabled = $bungalow->isReserved(
                                                        old('startDate'),
                                                        old('endDate'),
                                                    );
                                                }
                                            @endphp
                                            <option value="{{ $bungalow->id }}"
                                                {{ old('bungalowId') == $bungalow->id ? 'selected' : '' }}
                                                {{ $isDisabled ? 'disabled' : '' }}>
                                                {{ $bungalow->codeBungalow }}
                                                @if ($isDisabled)
                                                    (Non disponible)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- Image du bungalow mer -->
                                    <div id="bungalowMerImageContainer" class="d-none mt-3 text-center">
                                        <img id="bungalowMerImage" class="img-fluid rounded"
                                            style="max-height: 200px;" alt="Aperçu du bungalow mer"
                                            src="{{ asset('media/bungalow/carou-bung.webp') }}">
                                        <p class="mt-2 text-muted"><small>Bungalow vue mer</small></p>
                                    </div>
                                </div>
                                <p class="text-center mt-2">
                                    <small>Capacité maximale: 2 personnes par bungalow</small>
                                </p>
                            </div>

                            <!-- Bungalows Jardin -->
                            <div id="bungalowJardinContainer"
                                class="{{ old('typeBungalow') == 'jardin' ? '' : 'd-none' }} mt-4">
                                <h3 class="mb-3 text-center">Choisissez votre bungalow côté jardin</h3>
                                <div class="form-group d-flex justify-content-center flex-column align-items-center">
                                    <select name="bungalowId" id="bungalowJardinSelect" class="form-select w-50 mb-3"
                                        {{ old('typeBungalow') != 'jardin' ? 'disabled' : '' }}>
                                        <option value="">Sélectionnez un bungalow</option>
                                        @foreach ($bungalowsJardin as $bungalow)
                                            @php
                                                $isDisabled = false;
                                                if (old('startDate') && old('endDate')) {
                                                    $isDisabled = $bungalow->isReserved(
                                                        old('startDate'),
                                                        old('endDate'),
                                                    );
                                                }
                                            @endphp
                                            <option value="{{ $bungalow->id }}"
                                                {{ old('bungalowId') == $bungalow->id ? 'selected' : '' }}
                                                {{ $isDisabled ? 'disabled' : '' }}>
                                                {{ $bungalow->codeBungalow }}
                                                @if ($isDisabled)
                                                    (Non disponible)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- Image du bungalow jardin -->
                                    <div id="bungalowJardinImageContainer" class="d-none mt-3 text-center">
                                        <img id="bungalowJardinImage" class="img-fluid rounded"
                                            style="max-height: 200px;" alt="Aperçu du bungalow jardin"
                                            src="{{ asset('media/bungalow/carou-bung.webp') }}">
                                        <p class="mt-2 text-muted"><small>Bungalow côté jardin</small></p>
                                    </div>
                                </div>
                                <p class="text-center mt-2">
                                    <small>Capacité maximale: 4 personnes par bungalow</small>
                                </p>
                            </div>

                            @error('bungalowId')
                                <div class="text-danger text-center mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4" style="opacity: 0.3;">


                        <!-- Sélection du nombre de personnes -->
                        <x-person-counter :personCount="old('nbrPersonnes', 2)" :minPersons="1" :maxPersons="old('typeBungalow') == 'mer' ? 2 : 4"
                            fieldName="nbrPersonnes" label="Nombre de personnes" :capaciteInfo="old('typeBungalow') == 'mer'
                                ? 'Capacité maximale: 2 personnes par bungalow mer'
                                : (old('typeBungalow') == 'jardin'
                                    ? 'Capacité maximale: 4 personnes par bungalow jardin'
                                    : 'Capacité par bungalow')" />

                        <div class="d-flex justify-content-center mb-3">
                            <button type="submit" class="btn-reservation">
                                <span class="btn-text text-center">
                                    Confirmer votre réservation
                                </span>
                            </button>
                        </div>
                    </form>

                    @if (session('codeResaSejour'))
                        <x-activity-confirmation title="Réservation confirmée !" activityType="Bungalow"
                            :activityName="session('typeBungalow') == 'mer' ? 'Mer' : 'Jardin'" :startDate="session('startDate')" :endDate="session('endDate')" :personCount="session('nbrPersonnes')"
                            :reservationCode="session('codeResaSejour')" />
                    @endif
                </div>
            </div>
        </section>
    </main>
    <x-footer />

    <script src="{{ asset('js/forms/bungalow-form.js') }}"></script>
</body>

</html>
