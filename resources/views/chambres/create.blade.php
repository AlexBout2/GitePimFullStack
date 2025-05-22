<!-- resources/views/chambres/create.blade.php -->
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
    <meta name="robots" content="index, follow">
    <meta name="keywords" content="gîte, Poum, Nouvelle-Calédonie, bungalows">
    <meta name="geo.region" content="NC">
    <meta name="geo.placename" content="Poum">

    <meta property="og:title" content="Gîte Pim - Hébergement et activités à Poum">
    <meta property="og:description" content="réserver une chambre dans notre gîte.">
    <meta property="og:image" content="https://gitedepim.netlify.app/public/logo-gite-BLANC.webp">
    <meta property="og:url" content="{{ route('chambres.create') }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="Gîte Pim">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/front.css', 'resources/js/front.js', 'resources/css/modules/chambre.css', 'resources/js/forms/bungalow-form.js'])
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
                    <form class="col-md-6 col-11 mx-auto reservation-form mt-4" action="{{ route('sejour.store') }}"
                        method="POST">
                        @csrf


                        <x-date-selector mode="range" label="À quelle date voulez-vous réserver ?"
                            startLabel="Début du séjour" endLabel="Date fin" :startDate="old('startDate')" :endDate="old('endDate')" />

                        <!-- Ligne de séparation -->
                        <hr class="my-4" style="opacity: 0.3;">

                        <!-- Menu radio pour le choix du bungalow -->
                        <div class="form-group mb-3 text-center">
                            <label class="mb-3 text-center">Choisissez votre type d'hébergement</label>
                            <div class="d-flex justify-content-evenly text-center px-5 mt-2">
                                <div class="form-check text-center">
                                    <input type="radio" id="bungalowMer" name="bungalowType" value="Mer"
                                        {{ old('bungalowType') == 'mer' ? 'checked' : '' }}>
                                    <label for="bungalowMer">Bungalows Mer</label>
                                </div>
                                <div class="form-check text-center">
                                    <input type="radio" id="bungalowJardin" name="bungalowType" value="Jardin"
                                        {{ old('bungalowType') == 'jardin' ? 'checked' : '' }}>
                                    <label for="bungalowJardin">Bungalows Jardin</label>
                                </div>
                            </div>
                            @error('bungalowType')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Menu déroulant pour les bungalows de mer -->
                        <div class="d-flex justify-content-evenly form-group mb-3 d-none" id="bungalowMerContainer">
                            <select id="bungalowMerSelect" name="bungalowMerId"
                                class="form-select w-50 @error('bungalowMerId') is-invalid @enderror">
                                <option value="" disabled selected>Choisissez un bungalow Mer</option>
                                <option value="ME01" {{ old('bungalowMerId') == 'ME01' ? 'selected' : '' }}>Bungalow
                                    Mer 01</option>
                                <option value="ME02" {{ old('bungalowMerId') == 'ME02' ? 'selected' : '' }}>Bungalow
                                    Mer 02</option>
                                <option value="ME03" {{ old('bungalowMerId') == 'ME03' ? 'selected' : '' }}>Bungalow
                                    Mer 03</option>
                                <option value="ME04" {{ old('bungalowMerId') == 'ME04' ? 'selected' : '' }}>Bungalow
                                    Mer 04</option>
                                <option value="ME05" {{ old('bungalowMerId') == 'ME05' ? 'selected' : '' }}>Bungalow
                                    Mer 05</option>
                            </select>
                            @error('bungalowMerId')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="mer-availability-message" class="text-danger mt-2 text-center"></div>
                        </div>

                        <!-- Menu déroulant pour les bungalows de jardin -->
                        <div class="d-flex justify-content-evenly form-group mb-3 d-none" id="bungalowJardinContainer">
                            <select id="bungalowJardinSelect" name="bungalowJardinId"
                                class="form-select w-50 @error('bungalowJardinId') is-invalid @enderror">
                                <option value="" disabled selected>Choisissez un bungalow Jardin</option>
                                <option value="JA01" {{ old('bungalowJardinId') == 'JA01' ? 'selected' : '' }}>
                                    Bungalow Jardin 01</option>
                                <option value="JA02" {{ old('bungalowJardinId') == 'JA02' ? 'selected' : '' }}>
                                    Bungalow Jardin 02</option>
                                <option value="JA03" {{ old('bungalowJardinId') == 'JA03' ? 'selected' : '' }}>
                                    Bungalow Jardin 03</option>
                                <option value="JA04" {{ old('bungalowJardinId') == 'JA04' ? 'selected' : '' }}>
                                    Bungalow Jardin 04</option>
                                <option value="JA05" {{ old('bungalowJardinId') == 'JA05' ? 'selected' : '' }}>
                                    Bungalow Jardin 05</option>
                                <option value="JA06" {{ old('bungalowJardinId') == 'JA06' ? 'selected' : '' }}>
                                    Bungalow Jardin 06</option>
                                <option value="JA07" {{ old('bungalowJardinId') == 'JA07' ? 'selected' : '' }}>
                                    Bungalow Jardin 07</option>
                                <option value="JA08" {{ old('bungalowJardinId') == 'JA08' ? 'selected' : '' }}>
                                    Bungalow Jardin 08</option>
                                <option value="JA09" {{ old('bungalowJardinId') == 'JA09' ? 'selected' : '' }}>
                                    Bungalow Jardin 09</option>
                                <option value="JA10" {{ old('bungalowJardinId') == 'JA10' ? 'selected' : '' }}>
                                    Bungalow Jardin 10</option>
                            </select>
                            @error('bungalowJardinId')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="jardin-availability-message" class="text-danger mt-2 text-center"></div>
                        </div>

                        <x-form-errors />

                        <hr class="my-4" style="opacity: 0.3;">

                        <!-- Section nombre de personnes -->
                        @php
                            $selectedBungalowType = old('bungalowType', 'mer');
                        @endphp

                        <x-person-counter :personCount="old('personCount', 2)" :minPersons="1" :maxPersons="$selectedBungalowType == 'jardin' ? 4 : 2" fieldName="personCount"
                            label="Nombre de personnes" :showLabels="true" :capaciteInfo="$selectedBungalowType == 'mer'
                                ? 'Les bungalows Mer sont limités à 2 personnes maximum'
                                : 'Les bungalows Jardin peuvent accueillir jusqu\'à 4 personnes'" />

                        <div class="d-flex justify-content-center mb-3">
                            <button type="submit" class="btn-main text-center sejour-validation">
                                Confirmer votre réservation
                            </button>
                        </div>

                        @if (session('reservation'))
                            <div class="reservation-confirmation p-4 my-3 text-center border rounded">
                                <h2 class="mb-3">Réservation confirmée !</h2>
                                <div class="reservation-details text-start bg-light p-3 rounded mx-auto"
                                    style="max-width: 400px;">
                                    <p><strong>Type :</strong> Bungalow
                                        {{ session('reservation.type') === 'mer' ? 'mer' : 'jardin' }}</p>
                                    <p><strong>Dates :</strong> Du {{ session('reservation.startDate') }} au
                                        {{ session('reservation.endDate') }}</p>
                                    <p><strong>Personnes :</strong> {{ session('reservation.personCount') }}</p>
                                    <p class="mb-0"><strong>Durée :</strong> {{ session('reservation.duration') }}
                                        jour(s)</p>
                                </div>
                                <p class="mt-3 fs-3 fw-semibold">Conservez votre numéro de réservation. Vous en aurez
                                    besoin pour réserver des activités.</p>
                                <div class="row justify-content-center">
                                    <p class="fw-bold col-5 fs-4 p-2">Numéro de réservation :</p>
                                    <p class="reservation-number fs-4 bg-light p-2 rounded col-5">
                                        {{ session('reservation.number') }}</p>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </section>
    </main>
    <x-footer />

    @vite('resources/js/forms/bungalow-form.js')
</body>

</html>
