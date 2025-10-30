@extends('layouts.app')

@section('title', 'Справочник материалов инструмента')

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
            <span class="references-breadcrumbs__item active">Материалы инструмента</span>
        </nav>

        <!-- Заголовок -->
        <div class="references-header">
            <h1>Материалы режущего инструмента</h1>
            <p>Каталог материалов инструмента с характеристиками износостойкости и максимальными скоростями резания</p>
        </div>

        <!-- Навигация -->
        @include('references.partials.navigation')

        <!-- Данные -->
        <div class="data-section">
            <div class="data-section-header">
                <h2>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                    Каталог материалов инструмента ({{ $materials->count() }})
                </h2>
            </div>

            @if($materials->count() > 0)
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Материал инструмента</th>
                        <th>Тип</th>
                        <th>Макс. скорость (м/мин)</th>
                        <th>Коэффициент износостойкости</th>
                        <th>Применение</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($materials as $material)
                        <tr>
                            <td>
                                <strong>{{ $material->name }}</strong>
                            </td>
                            <td>
                                <span class="data-badge badge-primary">{{ $material->type }}</span>
                            </td>
                            <td>
                                <span class="value-high">{{ $material->max_cutting_speed }}</span>
                            </td>
                            <td>
                                @if($material->wear_resistance_factor >= 1.5)
                                    <span class="data-badge badge-success">Высокая</span>
                                @elseif($material->wear_resistance_factor >= 1.0)
                                    <span class="data-badge badge-warning">Средняя</span>
                                @else
                                    <span class="data-badge badge-danger">Низкая</span>
                                @endif
                                <small class="text-muted">({{ $material->wear_resistance_factor }})</small>
                            </td>
                            <td>
                                @if($material->type === 'Твердый сплав')
                                    <small>Универсальное применение</small>
                                @elseif($material->type === 'Быстрореж')
                                    <small>Низкоскоростная обработка</small>
                                @elseif($material->type === 'Керамика')
                                    <small>Высокоскоростная обработка</small>
                                @elseif($material->type === 'Сверхтвердый')
                                    <small>Твердые материалы</small>
                                @else
                                    <small>Специальное применение</small>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                    <h3>Данные не найдены</h3>
                    <p>В базе данных нет материалов инструмента</p>
                </div>
            @endif
        </div>
    </div>
@endsection
