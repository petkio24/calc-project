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

        @php
            $groupNames = [
                'hard_alloy' => 'Твердые сплавы',
                'high_speed_steel' => 'Быстрорежущие стали'
            ];
        @endphp

            <!-- Данные по группам -->
        @foreach($groupNames as $groupKey => $groupName)
            @if(isset($materials[$groupKey]) && $materials[$groupKey]->count() > 0)
                <div class="data-section">
                    <div class="data-section-header">
                        <h2>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                            </svg>
                            {{ $groupName }} ({{ $materials[$groupKey]->count() }})
                        </h2>
                    </div>

                    <table class="data-table">
                        <thead>
                        <tr>
                            <th>Материал инструмента</th>
                            <th>Марка</th>
                            <th>Макс. скорость (м/мин)</th>
                            <th>Коэффициент износостойкости</th>
                            <th>Применение</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($materials[$groupKey] as $material)
                            <tr>
                                <td>
                                    <strong>{{ $material->name }}</strong>
                                </td>
                                <td>
                                    <span class="data-badge badge-primary">{{ $material->grade }}</span>
                                </td>
                                <td>
                                    <span class="value-high">{{ $material->max_cutting_speed }}</span>
                                </td>
                                <td>
                                    @if($material->wear_resistance_factor >= 1.2)
                                        <span class="data-badge badge-success">Высокая</span>
                                    @elseif($material->wear_resistance_factor >= 0.8)
                                        <span class="data-badge badge-warning">Средняя</span>
                                    @else
                                        <span class="data-badge badge-danger">Низкая</span>
                                    @endif
                                    <small class="text-muted">({{ $material->wear_resistance_factor }})</small>
                                </td>
                                <td>
                                    <small>{{ $material->application_notes }}</small>
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
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
                <h3>Данные не найдены</h3>
                <p>В базе данных нет материалов инструмента</p>
            </div>
        @endif
    </div>
@endsection
