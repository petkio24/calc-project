@extends('layouts.app')

@section('title', 'Справочник материалов для фрезерования')

@section('styles')
    <link href="{{ asset('css/references.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="references-container">
        <!-- Хлебные крошки -->
        <nav class="references-breadcrumbs">
            <a href="{{ route('home') }}" class="references-breadcrumbs__item">Главная</a>
            <span class="references-breadcrumbs__separator">›</span>
            <a href="{{ route('references.index') }}" class="references-breadcrumbs__item">Справочники</a>
            <span class="references-breadcrumbs__separator">›</span>
            <span class="references-breadcrumbs__item active">Материалы для фрезерования</span>
        </nav>

        <!-- Заголовок -->
        <div class="references-header">
            <h1>Материалы для фрезерования</h1>
            <p>Справочник материалов заготовок с рекомендованными режимами резания для фрезерных операций</p>
        </div>

        <!-- Навигация -->
        @include('references.partials.navigation')

        <!-- Данные -->
        <div class="data-section">
            <div class="data-section-header">
                <h2>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                        <line x1="3" y1="9" x2="21" y2="9"></line>
                        <line x1="3" y1="15" x2="21" y2="15"></line>
                    </svg>
                    Каталог материалов ({{ $materials->count() }})
                </h2>
            </div>

            @if($materials->count() > 0)
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Материал</th>
                        <th>Скорость резания (м/мин)</th>
                        <th>Подача на зуб (мм/зуб)</th>
                        <th>Коэффициент мощности</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($materials as $material)
                        <tr>
                            <td>
                                <strong>{{ $material->name }}</strong>
                            </td>
                            <td>
                                <div class="range-display">
                                    <span class="value-low">{{ $material->cutting_speed_min }}</span>
                                    <span class="range-separator">-</span>
                                    <span class="value-high">{{ $material->cutting_speed_max }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="range-display">
                                    <span class="value-low">{{ $material->feed_per_tooth_min }}</span>
                                    <span class="range-separator">-</span>
                                    <span class="value-high">{{ $material->feed_per_tooth_max }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="value-medium">{{ $material->power_factor }}</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                        <line x1="3" y1="9" x2="21" y2="9"></line>
                        <line x1="3" y1="15" x2="21" y2="15"></line>
                    </svg>
                    <h3>Данные не найдены</h3>
                    <p>В базе данных нет материалов для фрезерования</p>
                </div>
            @endif
        </div>
    </div>
@endsection
