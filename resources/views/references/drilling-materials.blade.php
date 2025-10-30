@extends('layouts.app')

@section('title', 'Справочник материалов для сверления')

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
            <span class="references-breadcrumbs__item active">Материалы для сверления</span>
        </nav>

        <!-- Заголовок -->
        <div class="references-header">
            <h1>Материалы для сверления</h1>
            <p>Справочник материалов заготовок с рекомендованными режимами резания для сверлильных операций</p>
        </div>

        <!-- Навигация -->
        @include('references.partials.navigation')

        <!-- Данные -->
        <div class="data-section">
            <div class="data-section-header">
                <h2>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="2" x2="12" y2="6"></line>
                        <line x1="12" y1="18" x2="12" y2="22"></line>
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
                        <th>Подача (мм/об)</th>
                        <th>Коэффициент мощности</th>
                        <th>Тип материала</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($materials as $material)
                        <tr>
                            <td>
                                <strong>{{ $material->name }}</strong>
                                @if($material->hardness_range)
                                    <br><small class="text-muted">{{ $material->hardness_range }}</small>
                                @endif
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
                                    <span class="value-low">{{ $material->feed_per_rev_min }}</span>
                                    <span class="range-separator">-</span>
                                    <span class="value-high">{{ $material->feed_per_rev_max }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="value-medium">{{ $material->power_factor }}</span>
                            </td>
                            <td>
                                @if($material->material_type)
                                    <span class="data-badge badge-primary">{{ $material->material_type }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <h3>Данные не найдены</h3>
                    <p>В базе данных нет материалов для сверления</p>
                </div>
            @endif
        </div>
    </div>
@endsection
