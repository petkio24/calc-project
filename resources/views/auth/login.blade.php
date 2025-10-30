@extends('layouts.app')

@section('title', 'Вход в систему')

@section('styles')
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="auth-container">
        <div class="auth-wrapper">
            <div class="auth-card">
                <!-- Заголовок -->
                <div class="auth-header">
                    <h1>Добро пожаловать</h1>
                    <p>Войдите в свою учетную запись</p>
                </div>

                <!-- Форма входа -->
                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email адрес</label>
                        <input id="email" type="email" class="form-input @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                               placeholder="Введите ваш email">
                        @error('email')
                        <span class="error-message" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Пароль</label>
                        <input id="password" type="password" class="form-input @error('password') is-invalid @enderror"
                               name="password" required autocomplete="current-password"
                               placeholder="Введите ваш пароль">
                        @error('password')
                        <span class="error-message" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            Запомнить меня
                        </label>
                    </div>

                    <button type="submit" class="auth-button">
                        Войти в систему
                    </button>

                    <div class="auth-links">
                        @if (Route::has('password.request'))
                            <a class="auth-link" href="{{ route('password.request') }}">
                                Забыли пароль?
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a class="auth-link" href="{{ route('register') }}">
                                Создать аккаунт
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Декоративный элемент -->
            <div class="auth-decoration">
                <div class="decoration-content">
                    <div class="decoration-icon">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                    </div>
                    <h3>CalcProject</h3>
                    <p>Профессиональные расчеты режимов резания</p>
                </div>
            </div>
        </div>
    </div>
@endsection
