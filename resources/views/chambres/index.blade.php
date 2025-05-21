<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gîte Pim - Chambre</title>

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
    <meta property="og:image" content="{{ asset('media/logo-gite-BLANC.webp') }}">
    <meta property="og:url" content="{{ route('chambres.index') }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="Gîte Pim">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/front.css', 'resources/js/front.js'])

    <link rel="canonical" href="{{ route('chambres.index') }}">
</head>

<body>
    <x-navbar />

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-container">
                <div class="hero-text-box glass-effect">
                    <h1 class="text-shadow">Vivez une séjour Inoubliable</h1>
                    <h2 class="text-shadow">
                        Nos chambres offrent confort et calme
                    </h2>
                </div>
                <a href="{{ route('chambres.create') }}" class="btn-main" aria-label="Réservez une chambre">
                    <span class="btn-text">RÉSERVEZ</span>
                </a>
            </div>
        </section>

        <!-- Présentation -->
        <section class="presentation container-fluid py-3">
            <div>
                <h1>Découvrez nos Bungalow Mer et Jardin</h1>
            </div>

            <!-- Les 3 Photos des bungalows -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <img src="{{ Vite::asset('resources/media/chambres/Bungalow-Jardin-03.png') }}"
                        class="card-img-top img-fluid rounded" alt="Bungalow-Jardin-03"
                        style="height: 300px; object-fit: cover">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <img src="{{ Vite::asset('resources/media/chambres/Bungalow-Mer-03.png') }}"
                        class="card-img-top img-fluid rounded" alt="Bungalow-Mer-03"
                        style="height: 400px; object-fit: cover">
                </div>
                <div class="col-md-8 mb-3">
                    <img src="{{ Vite::asset('resources/media/chambres/Bungalow-Jardin-01.png') }}"
                        class="card-img-top img-fluid rounded" alt="Bungalow-Jardin-01"
                        style="height: 400px; object-fit: cover">
                </div>
            </div>
        </section>

        <!-- Option -->
        <section class="option container-fluid mt-5" role="presentation">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <img src="{{ Vite::asset('resources/media/chambres/Bungalow-Mer-01.png') }}"
                            class="card-img-top img-fluid" alt="Bungalow-Mer-01"
                            style="height: 400px; object-fit: cover">
                    </div>
                    <article class="col-md-6">
                        <h1>Vivez une expérience unique face à l'océan.</h1>
                        <p class="fs-4">
                            Ces 5 bungalows Mer vous accueillent dans un cadre apaisant, mêlant élégance et confort.
                            Avec leur décoration lumineuse, leurs touches de bois naturel et leurs grandes baies
                            vitrées, ils offrent
                            une vue imprenable sur la mer.
                            Chaque bungalow dispose d'une terrasse privée pour admirer le coucher de soleil et profiter
                            de l'air
                            marin.
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Réservation -->
        <section class="resa bg-image p-5 mt-0"
            style="
        background-image: url('{{ Vite::asset('resources/media/chambres/Footer-01.jpg') }}');
        background-size: cover;
        background-position: center;
      ">
            <div class="text-white col-12 col-md-8 align-self-center mx-auto glass-effect text-center" role="banner">
                <h1 class="fs-2 fs-md-1 text-white">"N'attendez plus pour réserver"</h1>
                <a href="{{ route('chambres.create') }}" class="btn-main mb-2">
                    <span class="btn-text">RÉSERVEZ</span>
                </a>
            </div>
        </section>
    </main>

    <!-- Pied de page -->
    <x-footer />
</body>

</html>
