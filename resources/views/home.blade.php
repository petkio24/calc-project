@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 text-center">Выбор типа обработки</h3>
                    </div>
                    <div class="card-body">
                        <div class="operations-container">
                            <!-- Точение -->
                            <a href="{{ route('calculators.turning') }}" class="operation-card-link">
                                <div class="operation-card" data-operation="turning">
                                    <div class="card-content">
                                        <div class="card-icon">
                                            <div class="icon-circle">
                                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="8"></circle>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                    <line x1="12" y1="5" x2="12" y2="3"></line>
                                                    <line x1="12" y1="21" x2="12" y2="19"></line>
                                                    <line x1="5" y1="12" x2="3" y2="12"></line>
                                                    <line x1="21" y1="12" x2="19" y2="12"></line>
                                                </svg>
                                            </div>
                                        </div>
                                        <h4 class="operation-title">Точение</h4>
                                        <p class="operation-description">
                                            Расчет режимов резания для токарной обработки
                                        </p>
                                        <div class="operation-features">
                                            <span class="feature">Токарные станки</span>
                                            <span class="feature">Наружная обработка</span>
                                            <span class="feature">Внутренняя обработка</span>
                                        </div>
                                    </div>
                                    <div class="card-hover">
                                        <span class="hover-text">Перейти к расчету</span>
                                    </div>
                                </div>
                            </a>

                            <!-- Сверление -->
                            <a href="{{ route('calculators.drilling') }}" class="operation-card-link"
                               style="text-decoration: none; color: inherit; display: block;">
                                <div class="operation-card" data-operation="drilling" style="cursor: pointer;">
                                    <!-- остальной код без изменений -->
                                    <div class="card-content">
                                        <div class="card-icon">
                                            <div class="icon-circle">
                                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="2" x2="12" y2="6"></line>
                                                    <line x1="12" y1="18" x2="12" y2="22"></line>
                                                    <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                                                    <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                                                    <line x1="2" y1="12" x2="6" y2="12"></line>
                                                    <line x1="18" y1="12" x2="22" y2="12"></line>
                                                    <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                                                    <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                                                </svg>
                                            </div>
                                        </div>
                                        <h4 class="operation-title">Сверление</h4>
                                        <p class="operation-description">
                                            Расчет режимов резания для сверлильных операций
                                        </p>
                                        <div class="operation-features">
                                            <span class="feature">Сверлильные станки</span>
                                            <span class="feature">Отверстия</span>
                                            <span class="feature">Рассверливание</span>
                                        </div>
                                    </div>
                                    <div class="card-hover">
                                        <span class="hover-text">Перейти к расчету</span>
                                    </div>
                                </div>
                            </a>

                            <!-- Фрезерование -->
                            <a href="{{ route('calculators.milling') }}" class="operation-card-link">
                                <div class="operation-card" data-operation="milling">
                                    <div class="card-content">
                                        <div class="card-icon">
                                            <div class="icon-circle">
                                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                                    <line x1="3" y1="9" x2="21" y2="9"></line>
                                                    <line x1="3" y1="15" x2="21" y2="15"></line>
                                                    <line x1="9" y1="3" x2="9" y2="21"></line>
                                                    <line x1="15" y1="3" x2="15" y2="21"></line>
                                                </svg>
                                            </div>
                                        </div>
                                        <h4 class="operation-title">Фрезерование</h4>
                                        <p class="operation-description">
                                            Расчет режимов резания для фрезерной обработки
                                        </p>
                                        <div class="operation-features">
                                            <span class="feature">Фрезерные станки</span>
                                            <span class="feature">Плоскости</span>
                                            <span class="feature">Контуры</span>
                                        </div>
                                    </div>
                                    <div class="card-hover">
                                        <span class="hover-text">Перейти к расчету</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
