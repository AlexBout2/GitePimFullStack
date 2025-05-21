<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gîte Pim - Page d’accueil</title>

    <!-- Préconnexions -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdn.bootstrap.com">

    <!-- Métadonnées SEO -->
    <meta name="description"
        content="Découvrez le Gîte Pim de Poum, avec ses bungalows vue mer ou jardin, son restaurant et ses activités: randonnée équestre, kayak et visites guidées">
    <meta name="author" content="Gîte Pim">
    <meta name="robots" content="index, follow">
    <meta name="keywords" content="gîte, Poum, Nouvelle-Calédonie, bungalows, randonnée équestre, kayak, hébergement">
    <meta name="geo.region" content="NC">
    <meta name="geo.placename" content="Poum">

    <!-- Open Graph pour partage réseaux sociaux -->
    <meta property="og:title" content="Gîte Pim - Hébergement et activités à Poum">
    <meta property="og:description"
        content="Séjournez dans nos bungalows confortables et profitez de nos activités: kayak, randonnées équestres et plus encore.">
    <meta property="og:image" content="https://gitedepim.netlify.app/public/media/logo-gite-BLANC.webp">
    <meta property="og:url" content="https://gitedepim.netlify.app/index.html">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="Gîte Pim">


    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/front.css', 'resources/js/front.js'])


    <!-- Canonical URL (importante pour éviter le contenu dupliqué) -->
    <link rel="canonical" href="https://gitedepim.netlify.app/index.html">
</head>


<body>
    <x-navbar />
    <main>
        <!-- Contenu principal -->
        <section class="hero">
            <div class="hero-container-bg">
                <div class="hero-text-box glass-effect col-12 col-md-5 col-md-5">
                    <h1 class="text-shadow text-white">Bienvenue au gîte Pim</h1>
                    <h2 class="text-shadow text-white">
                        Nous chambres offres confort et calme.
                    </h2>
                </div>
                <a href="{{ route('chambres.index') }}" class="btn-main btn-lg fs-5 mt-2">
                    En savoir +
                </a>
            </div>
        </section>

        <!-- Bungalows -->
        <section id="Bungalows">
            <div>
                <div class="container-fluid mt-5">
                    <h1>Nos bungalows</h1>

                    <!-- Conteneur des spécialités avec espacement -->
                    <div class="row mt-4 mb-5 d-flex flex-column flex-md-row justify-content-around g-3">
                        <!-- Bungalows jardin -->
                        <div class="main-item bungjar-main col-12 col-md-5 rounded align-content-center">
                            <div class="glass-effect p-1">
                                <h1 class="text-white text-shadow">
                                    Bungalow Jardin
                                </h1>
                                <p class="text-white text-shadow fs-3">
                                    Spacieux et confortables dans un cadre de verdoyant !
                                </p>
                            </div>
                            <button type="button" class="btn-main btn-lg fs-5 mt-2"
                                onclick="window.location.href='modules/chambre/chambre-index.html';">
                                En savoir +
                            </button>
                        </div>

                        <!-- Section Côté Mer -->
                        <div class="main-item bungsea-main col-12 col-md-5 rounded align-content-center">
                            <div class="glass-effect p-1">
                                <h1 class="text-white text-shadow">
                                    Bungalow Vue Mer
                                </h1>
                                <h2 class="text-white text-shadow fs-3">
                                    Laissez vous bercer par le son des vagues sur la terrasse !
                                </h2>
                            </div>
                            <a href="{{ route('chambres.index') }}" class="btn-main btn-lg fs-5 mt-2 text-center">
                                En savoir +
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="activites">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <h1>Nos activités</h1>
                    </div>
                    <div class="row mx-auto d-none d-sm-block">
                        <div id="carouselActivite" class="carousel slide" data-bs-touch="false" data-bs-ride="carousel"
                            aria-labelledby="activites-desktop-label">
                            <div class="carousel-inner">

                                <!-- Groupe de carte 1 Carousel desk/tab -->
                                <div class="carousel-item active">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="card h-100 shadow-sm">
                                                <img class="img-fluid" alt="Visite du bagne"
                                                    src="{{ asset('media/bagne/carou-bagne.webp') }}">
                                                <div class="card-body d-flex flex-column">
                                                    <h3 class="card-title">Visite du bagne</h3>
                                                    <p class="card-text flex-grow-1">Replongez avec notre guide dans
                                                        l'histoire du pays sur l'île de Pam.
                                                    </p>
                                                    <a href="{{ route('bagne.index') }}"
                                                        class="btn-main-small btn-lg fs-6 mt-auto text-center">
                                                        En savoir +
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-4 d-none d-sm-block">
                                            <div class="card h-100 shadow-sm">
                                                <img class="img-fluid" alt="Randonnée équestre"
                                                    src="{{ asset('media/cheval/carou-cheval.webp') }}">
                                                <div class="card-body d-flex flex-column">
                                                    <h3 class="card-title">Randonnée équestre</h3>
                                                    <p class="card-text flex-grow-1">De belles balades dans la nature
                                                        avec notre guide. À ne pas
                                                        manquer !
                                                    </p>
                                                    <a href="{{ route('cheval.index') }}"
                                                        class="btn-main-small btn-lg fs-6 mt-auto text-center">
                                                        En savoir +
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-4 d-none d-sm-block">
                                            <div class="card h-100 shadow-sm">
                                                <img class="img-fluid" alt="Notre garderie"
                                                    src="{{ asset('media/garderie/carou-gard.webp') }}">
                                                <div class="card-body d-flex flex-column">
                                                    <h3 class="card-title">Notre garderie</h3>
                                                    <p class="card-text flex-grow-1">Nous garderons vos enfants si ils
                                                        sont trop petits pour
                                                        certaines activités.
                                                    </p>
                                                    <a href="{{ route('garderie.index') }}"
                                                        class="btn-main-small btn-lg fs-6 mt-auto text-center">
                                                        En savoir +
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Groupe de carte 2 Carousel desk/tab -->
                                <div class="carousel-item">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="card h-100 shadow-sm">
                                                <img class="img-fluid" alt="Sortie kayak"
                                                    src="{{ asset('media/kayak/carou-kayak.webp') }}">
                                                <div class="card-body d-flex flex-column">
                                                    <h3 class="card-title">Sortie kayak</h3>
                                                    <p class="card-text flex-grow-1">Balades détente ou sportive en
                                                        kayak sur le lagon. Vivez
                                                        notre
                                                        lagon!
                                                    </p>
                                                    <a href="{{ route('kayak.index') }}"
                                                        class="btn-main-small btn-lg fs-6 mt-auto text-center">
                                                        En savoir +
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-4 d-none d-sm-block">
                                            <div class="card h-100 shadow-sm">
                                                <img class="img-fluid" alt="Restaurant Pim"
                                                    src="{{ asset('media/resto/carou-repas.webp') }}">
                                                <div class="card-body d-flex flex-column">
                                                    <h3 class="card-title">Restaurant Pim</h3>
                                                    <p class="card-text flex-grow-1">Pour les gourmets et les
                                                        gourmands, notre carte saura vous
                                                        ravir
                                                        !
                                                    </p>
                                                    <a href="{{ route('repas.index') }}"
                                                        class="btn-main-small btn-lg fs-6 mt-auto text-center">
                                                        En savoir +
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-4 d-none d-sm-block">
                                            <div class="card h-100 shadow-sm">
                                                <img class="img-fluid" alt="Nos bungalows"
                                                    src="{{ asset('media/bungalow/carou-bung.webp') }}">
                                                <div class="card-body d-flex flex-column">
                                                    <h3 class="card-title">Nos bungalows</h3>
                                                    <p class="card-text flex-grow-1">Jardin ou vue mer, choisissez
                                                        votre bungalow idéal pour votre
                                                        séjour.
                                                    </p>
                                                    <a href="{{ route('chambres.index') }}"
                                                        class="btn-main-small btn-lg fs-6 mt-auto text-center">
                                                        En savoir +
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselActivite"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Précédent</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselActivite"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Suivant</span>
                            </button>
                        </div>
                    </div>

                    <!-- Carousel Mobile -->
                    <div class="row mx-auto d-sm-none">
                        <div id="carouselActiviteMobile" class="carousel slide" data-bs-touch="false"
                            data-bs-ride="carousel" aria-labelledby="activites-mobile-label">
                            <div class="carousel-inner">

                                <div class="carousel-item active d-sm-none">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card h-100 shadow-sm">
                                                <img class="img-fluid" alt="Visite du bagne"
                                                    src="{{ asset('media/bagne/carou-bagne.webp') }}">
                                                <div class="card-body d-flex flex-column">
                                                    <h3 class="card-title">Visite du bagne</h3>
                                                    <p class="card-text flex-grow-1">Replongez avec notre guide dans
                                                        l'histoire du pays sur l'île
                                                        de
                                                        Pam.</p>
                                                    <a href="{{ route('bagne.index') }}"
                                                        class="btn-main-small btn-lg fs-6 mt-auto text-center">
                                                        En savoir +
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="carousel-item d-sm-none">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card h-100 shadow-sm">
                                                <img class="img-fluid" alt="Randonnée équestre"
                                                    src="{{ asset('media/cheval/carou-cheval.webp') }}">
                                                <div class="card-body d-flex flex-column">
                                                    <h3 class="card-title">Randonnée équestre</h3>
                                                    <p class="card-text flex-grow-1">De belles balades dans la nature
                                                        avec notre guide. À ne pas
                                                        manquer !</p>
                                                    <a href="{{ route('cheval.index') }}"
                                                        class="btn-main-small btn-lg fs-6 mt-auto text-center">
                                                        En savoir +
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="carousel-item d-sm-none">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card h-100 shadow-sm">
                                                <img class="img-fluid" alt="Sortie kayak"
                                                    src="{{ asset('media/kayak/carou-kayak.webp') }}" />
                                                <div class="card-body d-flex flex-column">
                                                    <h3 class="card-title">Sortie kayak</h3>
                                                    <p class="card-text flex-grow-1">Balades détente ou sportive en
                                                        kayak sur le lagon. Vivez
                                                        notre
                                                        lagon!
                                                    </p>
                                                    <a href="{{ route('kayak.index') }}"
                                                        class="btn-main-small btn-lg fs-6 mt-auto text-center">
                                                        En savoir +
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="carousel-item d-sm-none">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card h-100 shadow-sm">
                                                <img class="img-fluid" alt="Notre garderie"
                                                    src="{{ asset('media/garderie/carou-gard.webp') }}">
                                                <div class="card-body d-flex flex-column">
                                                    <h3 class="card-title">Notre garderie</h3>
                                                    <p class="card-text flex-grow-1">Vous souhaitez réaliser les
                                                        activités entre adultes,
                                                        confiez-nous
                                                        vos trésors.
                                                    </p>
                                                    <a href="{{ route('garderie.index') }}"
                                                        class="btn-main-small btn-lg fs-6 mt-auto text-center">
                                                        En savoir +
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="carousel-item d-sm-none">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card h-100 shadow-sm">
                                                <img class="img-fluid" alt="Restaurant Pim"
                                                    src="{{ asset('media/resto/carou-repas.webp') }}" />
                                                <div class="card-body d-flex flex-column">
                                                    <h3 class="card-title">Restaurant Pim</h3>
                                                    <p class="card-text flex-grow-1">Pour les gourmets et les
                                                        gourmands, notre carte saura vous
                                                        ravir
                                                        !
                                                    </p>
                                                    <a href="{{ route('repas.index') }}"
                                                        class="btn-main-small btn-lg fs-6 mt-auto text-center">
                                                        En savoir +
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="carousel-item d-sm-none">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card h-100 shadow-sm">
                                                <img class="img-fluid" alt="Nos bungalows"
                                                    src="{{ asset('media/bungalow/carou-bung.webp') }}">
                                                <div class="card-body d-flex flex-column">
                                                    <h3 class="card-title">Nos bungalows</h3>
                                                    <p class="card-text flex-grow-1">Jardin ou vue mer, choisissez
                                                        votre bungalow idéal pour votre
                                                        séjour.
                                                    </p>
                                                    <a href="{{ route('chambres.create') }}"
                                                        class="btn-main-small btn-lg fs-6 mt-auto text-center">
                                                        <span class="btn-text">En savoir +</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contrôles -->
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselActiviteMobile" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Précédent</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselActiviteMobile" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Suivant</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Séparation-->
        <div class="sep-main bg-image my-5" role="separator">
            <div class="d-flex flex-column justify-content-center align-items-center h-100">
                <p class="text-white text-center text-shadow fs-1">Venez vivre une aventure unique</p>
            </div>
        </div>

        <!-- témoignages-->
        <section class="testimonial py-5 bg-light" aria-labelledby="testimonials-heading">
            <div class="container-fluid">
                <h2 class="text-center mb-5">Nos clients ont adoré</h2>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <img src="{{ asset('media/resto/avatar1.png') }}" class="rounded-circle mb-3"
                                    alt="Client Avatar">
                                <h1 class="card-title fs-4">Billy Gaytes</h1>
                                <p class="card-text text-muted">USA, Chicago</p>
                                <p class="card-text">
                                    Notre séjour était fantastique ! Wonderful ! I'll be back,
                                    c'est certain !"
                                </p>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <img src="{{ asset('media/resto/avatar2.png') }}" class="rounded-circle mb-3"
                                    alt="Client Avatar">
                                <h1 class="card-title fs-4">Elsa Gorithme</h1>
                                <p class="card-text text-muted">France, Nice</p>
                                <p class="card-text">
                                    "Le personnel du gîte était au top et la qualité de la literie
                                    est exceptionnelle !"
                                </p>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <img src="{{ asset('media/resto/avatar3.png') }}" class="rounded-circle mb-3"
                                    alt="Client Avatar">
                                <h1 class="card-title fs-4">Olivia Jax</h1>
                                <p class="card-text text-muted">Vanuatu, Port-Vila</p>
                                <p class="card-text">
                                    "Incroyable site et le personnel était merveilleux. Je
                                    reviendrai !"
                                </p>
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <x-footer />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('./js/app.js') }}" defer></script>
</body>

</html>

</html>
