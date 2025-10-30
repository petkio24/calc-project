@extends('layouts.app')

@section('title', 'Подтверждение Email')

@section('styles')
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="auth-container">
        <div class="auth-wrapper">
            <div class="auth-card">
                <!-- Заголовок -->
                <div class="auth-header">
                    <div class="verification-icon">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <h1>Подтверждение Email</h1>
                    <p>Проверьте вашу электронную почту</p>
                </div>

                <!-- Содержимое верификации -->
                <div class="verification-content">
                    @if (session('resent'))
                        <div class="alert-success" role="alert">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            Новая ссылка для подтверждения была отправлена на ваш email адрес.
                        </div>
                    @endif

                    <div class="verification-text">
                        <p>Прежде чем продолжить, пожалуйста, проверьте вашу электронную почту и перейдите по ссылке для подтверждения.</p>
                        <p>Если вы не получили письмо</p>
                    </div>

                    <form class="resend-form" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="resend-button">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M23 4v6h-6"></path>
                                <path d="M1 20v-6h6"></path>
                                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                            </svg>
                            Отправить ссылку еще раз
                        </button>
                    </form>

                    <div class="auth-links">
                        <a class="auth-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Выйти из аккаунта
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

            <!-- Декоративный элемент -->
            <div class="auth-decoration">
                <div class="decoration-content">
                    <div class="decoration-icon">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    <h3>Проверьте почту</h3>
                    <p>Ссылка для подтверждения была отправлена на ваш email</p>
                    <div class="verification-tips">
                        <div class="tip-item">
                            <strong>Не пришло письмо?</strong>
                            <p>Проверьте папку "Спам" или запросите новую ссылку</p>
                        </div>
                        <div class="tip-item">
                            <strong>Неправильный email?</strong>
                            <p>Выйдите и зарегистрируйтесь с правильным адресом</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
