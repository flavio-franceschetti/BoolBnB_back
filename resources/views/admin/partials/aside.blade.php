<aside>
    <div class="d-none d-md-block">
        <ul class="aside-nav d-flex flex-column gap-2">
            <li class="{{ Request::is('admin') ? 'aside-active' : '' }}">
                <a href="{{ route('admin.home') }}">Home</a>
            </li>
            <li class="{{ Request::is('admin/apartments') ? 'aside-active' : '' }}">
                <a href="{{ route('admin.apartments.index') }}">Appartamenti</a>
            </li>
            <li class="{{ Request::is('admin/apartments/create') ? 'aside-active' : '' }}">
                <a href="{{ route('admin.apartments.create') }}">Inserisci appartamento</a>
            </li>
            <li class="{{ Request::is('admin/messages') ? 'aside-active' : '' }}">
                <a href="{{ route('admin.messages.index') }}">Messaggi</a>
            </li>
            <li class="{{ Request::is('admin/sponsorships') ? 'aside-active' : '' }}">
                <a href="{{ route('admin.sponsorships.index') }}">Sponsorizzazioni</a>
            </li>

            <!-- Menu a discesa per le statistiche -->

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

    .aside-active {
        border-bottom: 1px solid #28a745
    }
</style>