<header class="py-3 px-4 border-bottom" style="background-color: #161b22; border-color: #30363d !important;">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h4 class="text-white fw-bold mb-0">@yield('title')</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}" class="text-secondary text-decoration-none">Admin</a></li>
                    <li class="breadcrumb-item text-primary active" aria-current="page">@yield('title')</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex align-items-center gap-3">
            <a href="{{ url('/home') }}" target="_blank" class="btn btn-outline-secondary btn-sm d-none d-md-inline-flex align-items-center gap-2">
                <i class="bi bi-globe"></i>
                <span>View Store</span>
            </a>
            
            <div class="vr d-none d-md-block" style="opacity: 0.2;"></div>
            
            <div class="dropdown">
                <button class="btn btn-link text-decoration-none p-0 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="text-white fw-medium d-none d-md-inline">{{ Auth::user()->name ?? 'Admin' }}</span>
                    <i class="bi bi-chevron-down text-secondary small d-none d-md-inline"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="background-color: #21262d; border-color: #30363d;">
                    <li>
                        <span class="dropdown-item-text text-secondary small">Signed in as</span>
                        <span class="dropdown-item-text text-white fw-medium">{{ Auth::user()->email ?? 'admin@example.com' }}</span>
                    </li>
                    <li><hr class="dropdown-divider" style="border-color: #30363d;"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>