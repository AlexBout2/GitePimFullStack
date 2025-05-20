<footer class="footer py-5" aria-label="Footer" role="contentinfo">
    <div class="container">
        <div class="row">
            <!-- Colonne 1 : Logo et description -->
            <div class="col-6 col-sm-6 col-md-4">
            <a href="#" id="backToTop" aria-label="Retour en haut de page">
                <img src="{{ Vite::asset('resources/media/logo-gite-BLANC.webp') }}" alt="Logo Gîte Pim" 
                class="img-fluid mb-3 align-self-start" style="max-width: 50%;">
            </a>
                <h6 class="text-uppercase fw-bold text-white mb-3">Contact</h6>
                <p class="text-white"><i class="bi bi-geo-alt-fill me-2"></i> Îlot Pam, Poum</p>
                <p class="text-white"><i class="bi bi-telephone-fill me-2"></i> +687 12 34 56</p>
                <p class="text-white"><i class="bi bi-envelope-fill me-2"></i> contact@gitepim.nc</p>
                
            </div>
            <!-- Colonne 2 : Navigation principale -->
            <div class="col-6 col-sm-6 col-md-4">
                <h6 class="text-uppercase fw-bold text-white my-3">Navigation</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="text-white text-decoration-none">Accueil</a></li>
                    <li><a href="{{ route('chambres.index') }}" class="text-white text-decoration-none">Nos chambres</a></li>
                    <li><a href="{{ route('repas.index') }}" class="text-white text-decoration-none">Notre restaurant</a></li>
                    <li><a href="{{ route('garderie.index') }}" class="text-white text-decoration-none">La garderie</a></li></br></br>
                </ul>
                <h6 class="text-uppercase fw-bold text-white mb-3">Activité</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('cheval.index') }}" class="text-white text-decoration-none">Randonnée équestre</a></li>
                    <li><a href="{{ route('kayak.index') }}" class="text-white text-decoration-none">Sortie en kayak</a></li>
                    <li><a href="{{ route('bagne.index') }}" class="text-white text-decoration-none">Visite du bagne</a></li>
                </ul>
            </div>
            <!-- Colonne 3 : Contact et réseaux sociaux -->
            <div class="col-12 col-md-4 mt-3 mx-auto text-center">
            <img src="{{ Vite::asset('resources/media/cartepim.png') }}" alt="lieux Gîte Pim" width="300" class="mb-3">
            <h6 class="text-uppercase fw-bold text-white mt-4">Suivez-nous</h6>
            <div>
                <a href="#" class="text-white"><img src="{{ Vite::asset('resources/media/facebook.png') }}" alt="Facebook" aria-hidden="true" tabindex="-1" width="24"></a>
                <a href="#" class="text-white"><img src="{{ Vite::asset('resources/media/instagram.webp') }}" alt="Instagram" aria-hidden="true" tabindex="-1" width="24"></a>
                <a href="#" class="text-white"><img src="{{ Vite::asset('resources/media/twitter.webp') }}" alt="Twitter" aria-hidden="true" tabindex="-1" width="24"></a>
            </div>
            </div>
        </div>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('backToTop').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>
