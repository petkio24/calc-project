@extends('layouts.app')

@section('title', 'Справочник типов станков')

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
            <span class="references-breadcrumbs__item active">Типы станков</span>
        </nav>

        <!-- Заголовок -->
        <div class="references-header">
            <h1>Типы металлорежущих станков</h1>
            <p>Каталог типов станков с техническими характеристиками и ограничениями по мощности и оборотам</p>
        </div>

        <!-- Навигация -->
        @include('references.partials.navigation')

        <!-- Данные -->
        <div class="data-section">
            <div class="data-section-header">
                <h2>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    Каталог станков ({{ $machines->count() }})
                </h2>
            </div>

            @if($machines->count() > 0)
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Тип станка</th>
                        <th>Диапазон мощности</th>
                        <th>Макс. обороты</th>
                        <th>Коэффициент жесткости</th>
                        <th>Применение</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($machines as $machine)
                        <tr>
                            <td>
                                <strong>{{ $machine->name }}</strong>
                            </td>
                            <td>
                                <span class="data-badge badge-info">{{ $machine->power_range }}</span>
                            </td>
                            <td>
                                <span class="value-high">{{ number_format($machine->max_rpm, 0, ',', ' ') }} об/мин</span>
                            </td>
                            <td>
                                @if($machine->rigidity_factor > 1.2)
                                    <span class="data-badge badge-success">Высокая</span>
                                @elseif($machine->rigidity_factor > 0.9)
                                    <span class="data-badge badge-warning">Средняя</span>
                                @else
                                    <span class="data-badge badge-danger">Низкая</span>
                                @endif
                                <small class="text-muted">({{ $machine->rigidity_factor }})</small>
                            </td>
                            <td>
                                @if(str_contains($machine->name, 'CNC') || str_contains($machine->name, 'ЧПУ'))
                                    <small>Автоматизированная обработка</small>
                                @elseif(str_contains($machine->name, 'автомат'))
                                    <small>Серийное производство</small>
                                @elseif(str_contains($machine->name, 'настольный'))
                                    <small>Учебные/ремонтные работы</small>
                                @else
                                    <small>Универсальная обработка</small>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    </svg>
                    <h3>Данные не найдены</h3>
                    <p>В базе данных нет типов станков</p>
                </div>
            @endif
        </div>
    </div>
@endsection
