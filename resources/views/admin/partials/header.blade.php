<header>
    <div class="header-container">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-6 d-flex align-items-center ">
                    <a id="logo-bnb" href="http://localhost:5174/" target="_blank">
                        <img id="img-logo" src="{{ asset('logo_bnb.png') }}" alt="Vai al sito">
                    </a>
                    <a href="http://localhost:5174/" target="_blank">
                        <span class="text-black fs-3 fw-light">Boolbnb</span>
                    </a>
                </div>

                <div class="d-none d-md-flex col-6  align-items-center justify-content-end">@guest
                        <ul class="nav gap-2">
                            <li>
                                <a class="access text-white btn" href="{{ route('login') }}">Accedi</a>
                            </li>
                            <li>
                                <a class=" access text-white btn" href="{{ route('register') }}">Registrati</a>
                            </li>
                        </ul>
                    @else
                        <ul class="nav gap-2">
                            <li>
                                <a class="access btn text-white" href="{{ route('admin.home') }}">
                                    @if (Auth::user()->name)
                                        {{ Auth::user()->name }}
                                    @else
                                        {{ Auth::user()->email }}
                                    @endif
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="access btn text-white" type="submit">Esci</button>
                                </form>
                            </li>
                        </ul>
                    @endguest
                </div>


                <div class="dropdown d-flex d-md-none col-6 align-items-center justify-content-end px-4">
                    <button class="btn btn-offcanvas text-white" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i
                            class="fa-solid fa-bars"></i></button>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                        aria-labelledby="offcanvasRightLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasRightLabel">Area Personale</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            @auth
                                <ul class="aside-nav d-flex flex-column gap-4">
                                    <li><a href="{{ route('admin.home') }}"></i> Home</a></li>
                                    <li><a href="{{ route('admin.apartments.index') }}"></i> Appartamenti</a></li>
                                    <li><a href="{{ route('admin.apartments.create') }}"></i> Inserisci appartamento</a>
                                    </li>
                                    <li><a href="{{ route('admin.messages.index') }}"></i> Messaggi</a></li>
                                    <li><a href="{{ route('admin.sponsorships.index') }}"> Sponsorizzazioni</a></li>

                                    <!-- Menu a discesa per le statistiche -->
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="statisticsDropdown"
                                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-chart-line"></i> Statistiche
                                        </a>

                                        <ul class="dropdown-menu transition-dropdown" aria-labelledby="statisticsDropdown">
                                            @foreach (auth()->user()->apartments as $apartment)
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.apartments.statistics.show', $apartment->id) }}">
                                                        Statistiche di {{ $apartment->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>

                                    </li>
                                </ul>
                            @endauth
                            @guest
                                <button class="access btn text-white"><a href="{{ route('login') }}">Accedi</a></button>
                                <button class="access btn text-white"><a
                                        href="{{ route('register') }}">Registrati</a></button>
                            @else
                                <div class="d-flex gap-2">
                                    <button class="align-self-center btn btn-offcanvas "><a class="text-white"
                                            href="{{ route('admin.home') }}">{{ Auth::user()->name }}</a></button>

                                    <form class="align-self-center" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="btn btn-offcanvas text-white" type="submit">Esci</button>
                                    </form>
                                </div>

                            @endguest

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>
