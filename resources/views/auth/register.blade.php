@extends('layouts.app')

@section('title', 'Регистрация')

@section('styles')
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="auth-container">
        <div class="auth-wrapper">
            <div class="auth-card">
                <!-- Заголовок -->
                <div class="auth-header">
                    <h1>Создать аккаунт</h1>
                    <p>Присоединяйтесь к нашей платформе</p>
                </div>

                <!-- Форма регистрации -->
                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Имя пользователя</label>
                        <input id="name" type="text" class="form-input @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                               placeholder="Введите ваше имя">
                        @error('name')
                        <span class="error-message" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email адрес</label>
                        <input id="email" type="email" class="form-input @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email"
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
                               name="password" required autocomplete="new-password"
                               placeholder="Придумайте пароль">
                        @error('password')
                        <span class="error-message" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="form-label">Подтверждение пароля</label>
                        <input id="password-confirm" type="password" class="form-input"
                               name="password_confirmation" required autocomplete="new-password"
                               placeholder="Повторите пароль">
                    </div>

                    <button type="submit" class="auth-button">
                        Создать аккаунт
                    </button>

                    <div class="auth-links">
                        <a class="auth-link" href="{{ route('login') }}">
                            Уже есть аккаунт? Войти
                        </a>
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
                            <path d="M20 8v6M23 11h-6"></path>
                        </svg>
                    </div>
                    <h3>Присоединяйтесь</h3>
                    <p>Получите доступ ко всем возможностям калькуляторов</p>
                    <div class="features-list">
                        <div class="feature-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span>Расчеты режимов резания</span>
                        </div>
                        <div class="feature-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span>Сохранение результатов</span>
                        </div>
                        <div class="feature-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span>Профессиональные инструменты</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
