<nav class="navbar navbar-dark navbar-expand-lg bg-dark" aria-label="Navigation principale" role="navigation">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('home') }}">
      <img
        src="{{ asset('media/logo-gite-BLANC.webp') }}"
        alt="Accueil"
        width="160"
        height="60"
        class="d-inline-block align-text-top"
        title="Accueil"
      />
    </a>
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarNav"
      aria-controls="navbarNav"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white" href="{{ route('chambres.index') }}">Nos chambres</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="{{ route('repas.index') }}">Notre restaurant</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="{{ route('cheval.index') }}">Randonnée équestre</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="{{ route('kayak.index') }}">Sortie en kayak</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="{{ route('bagne.index') }}">Visite du bagne</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="{{ route('garderie.index') }}">La garderie</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
