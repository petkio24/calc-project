@extends('layouts.app')

@section('title', 'Поиск по справочникам')

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
            <span class="references-breadcrumbs__item active">Поиск</span>
        </nav>

        <!-- Заголовок -->
        <div class="references-header">
            <h1>Поиск по справочникам</h1>
            <p>Найдите нужные материалы, инструменты и оборудование</p>
        </div>

        <!-- Навигация -->
        @include('references.partials.navigation')

        <!-- Поисковая форма -->
        <div class="references-filters">
            <form action="{{ route('references.search') }}" method="GET">
                <div class="filter-group">
                    <div class="filter-item search-box">
                        <div class="search-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                        <input type="text" name="q" value="{{ $query }}" placeholder="Введите название материала, марку инструмента..." required>
                    </div>
                    <div class="filter-item">
                        <label>Тип поиска</label>
                        <select name="type">
                            <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Все категории</option>
                            <option value="materials" {{ $type === 'materials' ? 'selected' : '' }}>Материалы</option>
                            <option value="tools" {{ $type === 'tools' ? 'selected' : '' }}>Инструменты</option>
                            <option value="machines" {{ $type === 'machines' ? 'selected' : '' }}>Станки</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Найти</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Результаты поиска -->
        @if($query)
            <div class="search-results">
                @php $hasResults = false; @endphp

                    <!-- Материалы -->
                @if(isset($results['turning_materials']) && $results['turning_materials']->count())
                    @php $hasResults = true; @endphp
                    <div class="data-section fade-in">
                        <div class="data-section-header">
                            <h2>Материалы для точения ({{ $results['turning_materials']->count() }})</h2>
                        </div>
                        <table class="data-table">
                            <thead>
                            <tr>
                                <th>Материал</th>
                                <th>Группа</th>
                                <th>Твердость</th>
                                <th>Скорость резания</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results['turning_materials'] as $material)
                                <tr>
                                    <td><strong>{{ $material->name }}</strong></td>
                                    <td>{{ $material->material_group_name }}</td>
                                    <td>{{ $material->hardness_range }}</td>
                                    <td>{{ $material->cutting_speed_min }} - {{ $material->cutting_speed_max }} м/мин</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Добавьте аналогичные блоки для других типов материалов и инструментов -->

                @if(!$hasResults)
                    <div class="empty-state">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <h3>Ничего не найдено</h3>
                        <p>Попробуйте изменить поисковый запрос или выбрать другую категорию</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
