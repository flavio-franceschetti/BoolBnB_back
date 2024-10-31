<aside>
    <div class="d-none d-md-block">
        <ul class="aside-nav d-flex flex-column gap-2">
            <li><a href="{{ route('admin.home') }}"> Home</a></li>
            <li><a href="{{ route('admin.apartments.index') }}"> Appartamenti</a></li>
            <li><a href="{{ route('admin.apartments.create') }}"> Inserisci appartamento</a>
            </li>
            <li><a href="{{ route('admin.messages.index') }}"> Messaggi</a></li>
            <li><a href="{{ route('admin.sponsorships.index') }}"> Sponsorizzazioni</a></li>

            <!-- Menu a discesa per le statistiche -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="statisticsDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
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
    </div>
</aside>

<style>
    .dropdown-menu {
        display: none;
        opacity: 0;
        transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
        margin-top: 0;

    }

    .dropdown-menu.show {
        display: block;
        opacity: 1;
        transform: translateY(0);

    }

    .dropdown-menu:not(.show) {
        transform: translateY(-10px);

    }
</style>
