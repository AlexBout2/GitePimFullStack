<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gîte Pim - Location de kayak</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdn.bootstrap.com">

    <meta name="description" content="Visitez le lagon du gîte de pim en kayak">
    <meta name="author" content="Gîte Pim">
    <meta name="robots" content="index, follow">
    <meta name="keywords" content="gîte, Poum, Nouvelle-Calédonie, kayak, lagon, location">
    <meta name="geo.region" content="NC">
    <meta name="geo.placename" content="Poum">

    <meta property="og:title" content="Gîte Pim - location de kayak à Poum">
    <meta property="og:description" content="réserver un kayak.">
    <meta property="og:image" content="{{ asset('resources/media/logo-gite-BLANC.webp') }}">
    <meta property="og:url" content="{{ route('kayak.index') }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:site_name" content="Gîte Pim">

    <link rel="icon" type="image/x-icon" href="{{ asset('resources/media/logo.ico') }}">
    @vite(['resources/css/front.css', 'resources/js/front.js', 'resources/css/modules/kayak.css'])

    <link rel="canonical" href="{{ route('kayak.index') }}">
</head>

<body>
    <!-- Menu TOP -->
    <x-navbar />

    <!-- HERO -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-text-box glass-effect">
                <h1 class="text-shadow text-white">Vivez notre lagon !</h1>
                <h2 class="text-shadow text-white">
                    Découvrez notre île seul, en famille ou entre amies.
                </h2>
            </div>
            <a href="{{ route('kayak.create') }}" class="btn-main" aria-label="Réserver pour le kayak">
                <span class="btn-text">RÉSERVEZ</span>
            </a>
        </div>
    </section>

    <!-- Présentation -->
    <section class="activite not-mobile fs-3">
        <div class="row mx-5 mt-3 my-3 ">
            <article id="galerie-kayak" class="col-8 my-3 mx-auto">
                <div class="carousel js-flickity"
                    data-flickity-options='{ "freeScroll": true, "wrapAround": true, "lazyLoad": "2" }'>
                    <!-- Diapositive 1 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-1.jpg') }}" alt="randonnée en kayak en rivière">
                        <div class="panneau-texte hero-text-box glass-effect col-11 mx-auto">
                            <p class="text-shadow text-white text-center">
                                À l'origine, le kayak est inuit, confectionné avec des membrures
                                ployées, bordées et pontées de peaux, et manœuvré à l'aide d'une
                                pagaie simple ou double.
                            </p>
                        </div>
                    </div>

                    <!-- Diapositive 2 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-2.jpeg') }}" alt="kayaks sur une plage">
                    </div>

                    <!-- Diapositive 3 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-3.jpg') }}" alt="kayak sur l'embouchure de rivière">
                        <div class="panneau-texte hero-text-box glass-effect col-11 mx-auto">
                            <p class="text-shadow text-white text-center">
                                Aujourd'hui, un kayak est réalisé en toile imperméable, matériaux
                                synthétique ou pneumatique et manœuvré avec une pagaie double.
                            </p>
                        </div>
                    </div>

                    <!-- Diapositive 4 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-4.jpg') }}" alt="apprentis kayakistes en formation">
                    </div>

                    <!-- Diapositive 5 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-5.jpg') }}"
                            alt="kayak sur la mer proches de pin colonnaires">
                        <div class="panneau-texte hero-text-box glass-effect col-11 mx-auto">
                            <p class="text-shadow text-white text-center">
                                À la différence du canoë ou canot, le kayak se pratique à la
                                pagaie double en position assise.
                            </p>
                        </div>
                    </div>

                    <!-- Diapositive 6 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-6.jpg') }}" alt="image de randonnée en kayak">
                    </div>

                    <!-- Diapositive 7 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-7.webp') }}" alt="groupes d'amis sur un kayak">
                        <div class="panneau-texte hero-text-box glass-effect col-11 mx-auto">
                            <p class="text-shadow text-white text-center">
                                Certaines formes modernes comportent toujours un trou appelé
                                hiloire servant à entrer dans le bateau.
                            </p>
                        </div>
                    </div>

                    <!-- Diapositive 8 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-8.jpg') }}" alt="kayak sur la forêt noyée">
                    </div>

                    <!-- Diapositive 9 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-9.jpg') }}" alt="kayak au couché de soleil">
                        <div class="panneau-texte hero-text-box glass-effect col-11 mx-auto">
                            <p class="text-shadow text-white text-center">
                                C'est à votre tour de découvrir le kayak et notre gîte.
                            </p>
                        </div>
                    </div>
                </div>
            </article>
            <div class="row">
            </div>
        </div>
    </section>
    <section class="activite mobile">
        <div class="row">
            <div id="galerie-kayak-mobile" class="col-12 my-5">
                <div class="carousel js-flickity"
                    data-flickity-options='{ "freeScroll": true, "wrapAround": true, "lazyLoad": "2", "adaptiveHeight": true }'>
                    <!-- Diapositive 1 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-1.jpg') }}" alt="randonnée en kayak en rivière">
                        <div class="panneau-texte mobile-text glass-effect">
                            <p class="text-shadow text-white text-center">
                                À l'origine, le kayak est inuit, confectionné avec des membrures
                                ployées, bordées et pontées de peaux...
                            </p>
                        </div>
                    </div>

                    <!-- Diapositive 2 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-2.jpeg') }}" alt="kayaks sur une plage">
                    </div>

                    <!-- Diapositive 3 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-3.jpg') }}" alt="kayak sur l'embouchure de rivière">
                        <div class=" panneau-texte mobile-text glass-effect">
                            <p class="text-shadow text-white text-center">
                                Aujourd'hui, un kayak est réalisé en toile imperméable...
                            </p>
                        </div>
                    </div>

                    <!-- Diapositive 4 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-4.jpg') }}" alt="apprentis kayakistes en formation">
                    </div>

                    <!-- Diapositive 5 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-5.jpg') }}"
                            alt="kayak sur la mer proches de pin colonnaires">
                        <div class="panneau-texte mobile-text glass-effect">
                            <p class="text-shadow text-white text-center">
                                À la différence du canoë, le kayak se pratique à la
                                pagaie double...
                            </p>
                        </div>
                    </div>

                    <!-- Diapositive 6 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-6.jpg') }}" alt=" de randonnée en kayak">
                    </div>

                    <!-- Diapositive 7 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-7.webp') }}" alt="groupes d'amis sur un kayak">
                        <div class="panneau-texte mobile-text glass-effect">
                            <p class="text-shadow text-white text-center">
                                Certaines formes modernes comportent toujours un trou appelé
                                hiloire...
                            </p>
                        </div>
                    </div>

                    <!-- Diapositive 8 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-8.jpg') }}" alt="kayak sur la forêt noyée">
                    </div>

                    <!-- Diapositive 9 -->
                    <div class="panneau">
                        <img src="{{ asset('/media/kayak/image-9.jpg') }}" alt="kayak au couché de soleil">
                        <div class="panneau-texte mobile-text glass-effect">
                            <p class="text-shadow text-white text-center">
                                C'est à votre tour de découvrir le kayak et notre gîte.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bannière" role="banner">
        <div class="container-fluid text-shadow">
            <div class="row">
                <div class=" d-flex align-items-center img-resa-kayak">
                    <div class="text-white col-12 col-md-8 align-self-center mx-auto glass-effect text-center">
                        <h1 class="fs-2 fs-md-1 text-white">Conditions de réservation</h1>
                        <div class="row justify-content-center m">
                            <div class="col-12 col-md-8 ">
                                <p class="lh-sm text-white fs-2 fs-md-3">Gillet obligatoire</p>
                                <p class="lh-sm text-white fs-3 fs-md-3">Horaire : 9h à 16h</p>
                                <p class="lh-sm text-white fs-3 fs-md-3">Kayak simple : 2</p>
                                <p class="lh-sm text-white fs-3 fs-md-3">Kayak double : 3</p>
                            </div>
                            <div class="d-flex justify-content-center mb-3">
                                <a href="{{ route('kayak.create') }}" class="btn-main mx-auto"
                                    aria-label="Réserver pour votre kayak">
                                    <span class="btn-text">RÉSERVEZ</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pied de page -->
    <x-footer />

    <!-- Script -->
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
</body>

</html>
