@extends('layouts.app')

@section('title', 'Справочники')

@section('styles')
    <link href="{{ asset('css/references.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="references-container">
        <!-- Хлебные крошки -->
        <nav class="references-breadcrumbs">
            <a href="{{ route('home') }}" class="references-breadcrumbs__item">Главная</a>
            <span class="references-breadcrumbs__separator">›</span>
            <span class="references-breadcrumbs__item active">Справочники</span>
        </nav>

        <!-- Заголовок -->
        <div class="references-header">
            <h1>Технические справочники</h1>
            <p>Полная база данных материалов, инструментов и оборудования</p>
        </div>

        <!-- Навигация -->
        <div class="references-nav">
            <div class="nav-tabs">
                <div class="nav-tab">
                    <a href="{{ route('references.index') }}" class="nav-tab-link {{ $activeTab === 'overview' ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Обзор
                    </a>
                </div>
                <div class="nav-tab">
                    <a href="{{ route('references.drilling-materials') }}" class="nav-tab-link {{ $activeTab === 'drilling' ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="2" x2="12" y2="6"></line>
                            <line x1="12" y1="18" x2="12" y2="22"></line>
                        </svg>
                        Сверление
                    </a>
                </div>
                <div class="nav-tab">
                    <a href="{{ route('references.turning-materials') }}" class="nav-tab-link {{ $activeTab === 'turning' ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="8"></circle>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        Точение
                    </a>
                </div>
                <div class="nav-tab">
                    <a href="{{ route('references.milling-materials') }}" class="nav-tab-link {{ $activeTab === 'milling' ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="3" y1="15" x2="21" y2="15"></line>
                        </svg>
                        Фрезерование
                    </a>
                </div>
                <div class="nav-tab">
                    <a href="{{ route('references.tool-materials') }}" class="nav-tab-link {{ $activeTab === 'tools' ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                        </svg>
                        Инструменты
                    </a>
                </div>
                <div class="nav-tab">
                    <a href="{{ route('references.machine-types') }}" class="nav-tab-link {{ $activeTab === 'machines' ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        Станки
                    </a>
                </div>
            </div>
        </div>

        <!-- Карточки обзора -->
        <div class="overview-grid">
            <!-- Сверление -->
            <a href="{{ route('references.drilling-materials') }}" class="overview-card">
                <div class="overview-card-header">
                    <div class="overview-card-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="2" x2="12" y2="6"></line>
                            <line x1="12" y1="18" x2="12" y2="22"></line>
                        </svg>
                    </div>
                    <h3 class="overview-card-title">Материалы для сверления</h3>
                </div>
                <p class="overview-card-description">
                    Справочник материалов заготовок с рекомендованными скоростями резания и подачами для сверлильных операций.
                </p>
                <div class="overview-card-stats">
                    <span class="overview-card-count">{{ \App\Models\DrillingMaterial::count() }} материалов</span>
                    <span class="overview-card-link">
                    Перейти
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </span>
                </div>
            </a>

            <!-- Точение -->
            <a href="{{ route('references.turning-materials') }}" class="overview-card">
                <div class="overview-card-header">
                    <div class="overview-card-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="8"></circle>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </div>
                    <h3 class="overview-card-title">Материалы для точения</h3>
                </div>
                <p class="overview-card-description">
                    База данных материалов для токарной обработки с параметрами режимов резания и рекомендуемыми подачами.
                </p>
                <div class="overview-card-stats">
                    <span class="overview-card-count">{{ \App\Models\TurningMaterial::count() }} материалов</span>
                    <span class="overview-card-link">
                    Перейти
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </span>
                </div>
            </a>

            <!-- Фрезерование -->
            <a href="{{ route('references.milling-materials') }}" class="overview-card">
                <div class="overview-card-header">
                    <div class="overview-card-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="3" y1="15" x2="21" y2="15"></line>
                        </svg>
                    </div>
                    <h3 class="overview-card-title">Материалы для фрезерования</h3>
                </div>
                <p class="overview-card-description">
                    Справочник материалов для фрезерных операций с параметрами подач на зуб и скоростей резания.
                </p>
                <div class="overview-card-stats">
                    <span class="overview-card-count">{{ \App\Models\MillingMaterial::count() }} материалов</span>
                    <span class="overview-card-link">
                    Перейти
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </span>
                </div>
            </a>

            <!-- Материалы инструмента -->
            <a href="{{ route('references.tool-materials') }}" class="overview-card">
                <div class="overview-card-header">
                    <div class="overview-card-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                        </svg>
                    </div>
                    <h3 class="overview-card-title">Материалы инструмента</h3>
                </div>
                <p class="overview-card-description">
                    Каталог материалов режущего инструмента с характеристиками износостойкости и максимальными скоростями.
                </p>
                <div class="overview-card-stats">
                    <span class="overview-card-count">{{ \App\Models\ToolMaterial::count() }} материалов</span>
                    <span class="overview-card-link">
                    Перейти
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </span>
                </div>
            </a>

            <!-- Геометрия инструмента -->
            <a href="{{ route('references.tool-geometries') }}" class="overview-card">
                <div class="overview-card-header">
                    <div class="overview-card-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2"></polygon>
                            <line x1="12" y1="22" x2="12" y2="15.5"></line>
                            <polyline points="22 8.5 12 15.5 2 8.5"></polyline>
                            <polyline points="2 15.5 12 8.5 22 15.5"></polyline>
                            <line x1="12" y1="2" x2="12" y2="8.5"></line>
                        </svg>
                    </div>
                    <h3 class="overview-card-title">Геометрия инструмента</h3>
                </div>
                <p class="overview-card-description">
                    Справочник геометрических параметров режущего инструмента с углами резания и коэффициентами подач.
                </p>
                <div class="overview-card-stats">
                    <span class="overview-card-count">{{ \App\Models\ToolGeometry::count() }} геометрий</span>
                    <span class="overview-card-link">
                    Перейти
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </span>
                </div>
            </a>

            <!-- Типы станков -->
            <a href="{{ route('references.machine-types') }}" class="overview-card">
                <div class="overview-card-header">
                    <div class="overview-card-icon">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                    <h3 class="overview-card-title">Типы станков</h3>
                </div>
                <p class="overview-card-description">
                    Каталог типов металлорежущих станков с техническими характеристиками и ограничениями по мощности.
                </p>
                <div class="overview-card-stats">
                    <span class="overview-card-count">{{ \App\Models\MachineType::count() }} типов</span>
                    <span class="overview-card-link">
                    Перейти
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </span>
                </div>
            </a>
        </div>
    </div>
@endsection
