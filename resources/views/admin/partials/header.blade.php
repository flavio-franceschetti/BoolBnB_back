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

                <div class="dropdown d-flex d-md-none col-6 align-items-center justify-content-end">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa-solid fa-user"></i>
                    </button>
                    <ul class="dropdown-menu">
                        @guest
                            <li><a class="dropdown-item " href="{{ route('login') }}">Accedi</a></li>
                            <li> <a class="dropdown-item " href="{{ route('register') }}">Registrati</a></li>
                        @else
                            <li><a class="dropdown-item  " href="{{ route('admin.home') }}">{{ Auth::user()->name }}</a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item " type="submit">Esci</button>
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </div>
    </div>

</header>
