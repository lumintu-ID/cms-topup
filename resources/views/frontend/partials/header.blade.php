<header>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="/">
      <img src="{{ asset('assets/website/images/logo/esi.png') }}" alt="esi" height="40" class="d-inline-block align-text-center">
      ESI GAME SHOP
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link{{ $activeLink == 'home' ? ' active': '' }}" aria-current="page" href="{{ route('home') }}">Home</a>
        <a class="nav-link{{ $activeLink == 'games' ? ' active': '' }}" href="{{ route('games') }}">Games</a>
        <a class="nav-link{{ $activeLink == 'payment' ? ' active': '' }}" href="{{ route('payment') }}">Payment</a>
        <a class="nav-link" href="#">News</a>
        <a class="nav-link" href="#">Community</a>
        <a class="nav-link" href="#">Support</a>
      </div>
      <div class="menu-auth ms-auto text-center mt-4 mt-lg-0">
        <a class="nav-btn d-block d-lg-inline" aria-current="page" href="#"></a>
        <a class="nav-btn d-block d-lg-inline mt-3 mt-lg-0" href="#"></a>
        <a class="nav-btn d-block d-lg-inline mt-3 mt-lg-0" href="#"></a>
      </div>
    </div>
  </div>
</nav>
</header>