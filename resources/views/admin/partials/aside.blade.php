<aside class="text-bg-dark p-3">
    <ul class="aside-nav d-flex flex-column gap-2">
        <li><a href="{{ route('admin.home') }}"><i class="fa-solid fa-house"></i> Home</a></li>
        <li><a href="{{ route('admin.apartments.index') }}"><i class="fa-solid fa-house"></i> Appartamenti</a></li>
        <li><a href="{{ route('admin.apartments.create') }}"><i class="fa-solid fa-house"></i> Inserisci appartamento</a>
        </li>
        <li><a href="{{ route('admin.messages.index') }}"><i class="fa-solid fa-house"></i> Messaggi</a></li>
        <li><a href="{{ route('admin.sponsorships.index') }}"><i class="fa-solid fa-star"></i> Sponsorizzazioni</a></li>

        <!-- Menu a discesa per le statistiche -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="statisticsDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-chart-line"></i> Statistiche
            </a>
            <ul class="dropdown-menu transition-dropdown" aria-labelledby="statisticsDropdown">
                @foreach (auth()->user()->apartments as $apartment)
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.apartments.statistics.show', $apartment->id) }}">
                            Statistiche di {{ $apartment->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
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
