<header>


    <div class="container-fluid text-bg-dark d-flex justify-content-between p-4">
        <a class="fw-bold" href="{{ route('home') }}" target="_blank">Vai al sito</a>
        @guest
            <ul class="nav gap-2">
                <li>
                    <a href="{{ route('login') }}">Login</a>
                </li>
                <li>
                    <a href="{{ route('register') }}">Registrati</a>
                </li>
            </ul>
        @else
            <ul class="nav gap-2">
                <li>
                    <a href="{{ route('admin.home') }}">{{ Auth::user()->name }}</a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </li>
            </ul>
        @endguest
    </div>

</header>