<nav class="navbar navbar-expand bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold text-primary" href="{{ url('/') }}">
            <svg class="me-2" width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z" fill="currentColor"/>
                <path d="M5 9h14v10H5V9z" fill="white"/>
                <path d="M8 12h3v3H8v-3z" fill="currentColor"/>
            </svg>
            Campus Event
        </a>
        <div class="ms-auto">
            @auth
                <div class="d-flex align-items-center gap-2 me-2">

                    <a href="" class="btn btn-outline-primary rounded-pill px-4 fw-bold fs-6">Dashboard</a>
                    {{-- <a href="{{ route('dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold fs-6">Dashboard</a> --}}

                    {{-- add dropdown menu with logout option --}}
                    <div class="dropdown">
                        <button class="btn btn-outline-primary rounded-pill px-4 fw-bold" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bx-menu fs-4 align-middle'></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item fw-bold fs-6" href="{{ route('profile') }}">
                                    Profile
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item fw-bold fs-6">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-1 rounded-pill px-4 fw-bold">Log in</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">Register</a>
            @endauth
        </div>
    </div>
</nav>

<style>
    .navbar a {
        transition: all .15s ease-in-out;
    }
</style>