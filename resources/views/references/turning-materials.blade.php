@extends('layouts.app')

@section('title', 'Справочник материалов для точения')

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
            <span class="references-breadcrumbs__item active">Материалы для точения</span>
        </nav>

        <!-- Заголовок -->
        <div class="references-header">
            <h1>Материалы для точения</h1>
            <p>Справочник материалов заготовок с рекомендованными режимами резания для токарной обработки</p>
        </div>

        <!-- Навигация -->
        @include('references.partials.navigation')

        @php
            $groupNames = [
                'black_metals' => 'Черные металлы',
                'nonferrous_metals' => 'Цветные металлы',
                'non_metals' => 'Неметаллы'
            ];
        @endphp

            <!-- Данные по группам -->
        @foreach($groupNames as $groupKey => $groupName)
            @if(isset($materials[$groupKey]) && $materials[$groupKey]->count() > 0)
                <div class="data-section">
                    <div class="data-section-header">
                        <h2>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            {{ $groupName }} ({{ $materials[$groupKey]->count() }})
                        </h2>
                    </div>

                    <table class="data-table">
                        <thead>
                        <tr>
                            <th>Материал</th>
                            <th>Твердость</th>
                            <th>Скорость резания (м/мин)</th>
                            <th>Подача (мм/об)</th>
                            <th>Коэффициент мощности</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($materials[$groupKey] as $material)
                            <tr>
                                <td>
                                    <strong>{{ $material->name }}</strong>
                                </td>
                                <td>
                                    @if($material->hardness_range)
                                        <span class="data-badge badge-info">{{ $material->hardness_range }}</span>
                                    @else
                                        <span class="text-muted">—</span>
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
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endforeach

        <!-- Пустое состояние -->
        @if($materials->isEmpty())
            <div class="empty-state">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <h3>Данные не найдены</h3>
                <p>В базе данных нет материалов для точения</p>
            </div>
        @endif
    </div>
@endsection
