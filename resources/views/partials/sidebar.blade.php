<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}" aria-current="page"
                    href="{{ route('dashboard.index') }}">
                    <span data-feather="home" class="align-text-bottom"></span>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link  {{ request()->routeIs('dashboard.*') ? 'active' : '' }}" href="#">
                    <span data-feather="file" class="align-text-bottom"></span>
                    Jabatan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link  {{ request()->routeIs('dashboard.*') ? 'active' : '' }}" href="#">
                    <span data-feather="file" class="align-text-bottom"></span>
                    Karyawaan
                </a>
            </li>
        </ul>

        <form action="{{ route('auth.logout') }}" method="post"
            onsubmit="return confirm('Apakah anda yakin ingin keluar?')">
            @method('DELETE')
            @csrf
            <button class="w-full mt-4 d-block bg-transparent border-0 fw-bold text-danger px-3">Keluar</button>
        </form>
    </div>
</nav>