<nav class="navbar navbar-expand-md navbar-main shadow-sm">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Left Side - Navigation Menu -->
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link nav-item-main" href="#">Расчет режимов</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-item-main" href="#">Справочники</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-item-main" href="#">История расчетов</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link nav-item-main" href="{{ route('login') }}">Вход</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-item-main" href="{{ route('register') }}">Регистрация</a>
                    </li>
                @endauth
            </ul>

            <!-- Right Side - User Profile -->
            @auth
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item profile-item">
                        <div class="user-profile">
                            <span class="user-avatar">👤</span>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link logout-btn" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                            Выход
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
</nav>
