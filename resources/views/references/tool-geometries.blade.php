@extends('layouts.app')

@section('title', 'Справочник маркировки инструмента')

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
            <span class="references-breadcrumbs__item active">Маркировка инструмента</span>
        </nav>

        <!-- Заголовок -->
        <div class="references-header">
            <h1>Маркировка режущего инструмента</h1>
            <p>Справочник геометрических параметров инструмента с полной маркировкой пластин</p>
        </div>

        <!-- Навигация -->
        @include('references.partials.navigation')

        <!-- Данные -->
        <div class="data-section">
            <div class="data-section-header">
                <h2>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2"></polygon>
                        <line x1="12" y1="22" x2="12" y2="15.5"></line>
                        <polyline points="22 8.5 12 15.5 2 8.5"></polyline>
                        <polyline points="2 15.5 12 8.5 22 15.5"></polyline>
                        <line x1="12" y1="2" x2="12" y2="8.5"></line>
                    </svg>
                    Каталог маркировок ({{ $geometries->count() }})
                </h2>
            </div>

            @if($geometries->count() > 0)
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Маркировка</th>
                        <th>Форма</th>
                        <th>Задний угол</th>
                        <th>Класс точности</th>
                        <th>Стружколом</th>
                        <th>Длина кромки</th>
                        <th>Толщина</th>
                        <th>Радиус</th>
                        <th>Коэффициент подачи</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($geometries as $geometry)
                        <tr>
                            <td>
                                <strong>{{ $geometry->name }}</strong>
                            </td>
                            <td>
                                <span class="data-badge badge-primary">{{ $geometry->shape_name }}</span>
                            </td>
                            <td>
                                <span class="value-medium">{{ $geometry->clearance_angle }}°</span>
                            </td>
                            <td>
                                <span class="data-badge badge-info">{{ $geometry->tolerance_class_name }}</span>
                            </td>
                            <td>
                                <small>{{ $geometry->chipbreaker_type ?: 'Нет' }}</small>
                            </td>
                            <td>
                                <span class="value-medium">{{ $geometry->cutting_edge_length }} мм</span>
                            </td>
                            <td>
                                <span class="value-medium">{{ $geometry->insert_thickness }} мм</span>
                            </td>
                            <td>
                                <span class="data-badge badge-warning">R{{ $geometry->corner_radius }}</span>
                            </td>
                            <td>
                                @if($geometry->feed_factor > 1.0)
                                    <span class="data-badge badge-success">+{{ ($geometry->feed_factor - 1) * 100 }}%</span>
                                @elseif($geometry->feed_factor < 1.0)
                                    <span class="data-badge badge-danger">-{{ (1 - $geometry->feed_factor) * 100 }}%</span>
                                @else
                                    <span class="data-badge badge-info">стандарт</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2"></polygon>
                    </svg>
                    <h3>Данные не найдены</h3>
                    <p>В базе данных нет маркировок инструмента</p>
                </div>
            @endif
        </div>
    </div>
@endsection
