@extends('layouts.app')

@section('title', 'Калькулятор точения')

@section('styles')
    <link href="{{ asset('css/calculator_turning.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="calculator-container">
        <!-- Хлебные крошки -->
        <nav class="calc-breadcrumbs">
            <a href="{{ route('home') }}" class="calc-breadcrumbs__item">Главная</a>
            <span class="calc-breadcrumbs__separator">›</span>
            <span class="calc-breadcrumbs__item active">Калькулятор точения</span>
        </nav>

        <!-- Заголовок -->
        <div class="calc-header">
            <h1>Расчет режимов точения</h1>
            <h2>Оптимальные параметры токарной обработки</h2>
        </div>

        <!-- Форма калькулятора -->
        <form method="POST" action="{{ route('calculators.turning.calculate') }}">
            @csrf

            <div class="calc-grid">
                <!-- Основные параметры -->
                <div class="calc-section">
                    <h3 class="section-title">Параметры обработки</h3>

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="calc_diameter">Диаметр заготовки (мм)</label>
                        <input class="calc-input-group__input" id="calc_diameter"
                               name="diameter" type="number" step="0.01" placeholder="Введите диаметр"
                               value="{{ old('diameter') }}" required>
                    </div>

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="depth_of_cut">Глубина резания (мм)</label>
                        <input class="calc-input-group__input" id="depth_of_cut"
                               name="depth_of_cut" type="number" step="0.1" placeholder="Введите глубину"
                               value="{{ old('depth_of_cut') }}" required>
                    </div>

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="material_id">Материал заготовки</label>
                        <select class="calc-input-group__input" id="material_id" name="material_id" required>
                            <option value="">Выберите материал</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}"
                                    {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                    {{ $material->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Параметры станка -->
                <div class="calc-section">
                    <h3 class="section-title">Параметры станка</h3>

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="machine_power">Мощность станка (кВт)</label>
                        <input class="calc-input-group__input" id="machine_power"
                               name="machine_power" type="number" step="0.1" placeholder="Введите мощность"
                               value="{{ old('machine_power') }}" required>
                    </div>

                    <div class="calc-actions">
                        <button type="submit" class="btn-calculate">
                            Рассчитать параметры
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Результаты -->
        @if(isset($result))
            <div class="calc-results">
                <div class="results-header">
                    <h3>Результаты расчета</h3>
                    <div class="results-subtitle">Оптимальные режимы токарной обработки</div>
                </div>

                <div class="results-grid">
                    <div class="result-card">
                        <div class="result-label">Материал</div>
                        <div class="result-value">{{ $result['material'] }}</div>
                    </div>

                    <div class="result-card">
                        <div class="result-label">Диаметр заготовки</div>
                        <div class="result-value">{{ $result['diameter'] }} мм</div>
                    </div>

                    <div class="result-card">
                        <div class="result-label">Глубина резания</div>
                        <div class="result-value">{{ $result['depth_of_cut'] }} мм</div>
                    </div>

                    <div class="result-card">
                        <div class="result-label">Скорость резания</div>
                        <div class="result-value">{{ $result['cutting_speed'] }} м/мин</div>
                    </div>

                    <div class="result-card">
                        <div class="result-label">Подача на оборот</div>
                        <div class="result-value">{{ $result['feed'] }} мм/об</div>
                    </div>

                    <div class="result-card">
                        <div class="result-label">Обороты шпинделя</div>
                        <div class="result-value">{{ $result['spindle_rpm'] }} об/мин</div>
                    </div>

                    <div class="result-card {{ $result['is_power_sufficient'] ? 'success' : 'danger' }}">
                        <div class="result-label">Мощность резания</div>
                        <div class="result-value">{{ $result['cutting_power'] }} кВт</div>
                    </div>
                </div>

                <div class="power-status {{ $result['is_power_sufficient'] ? 'status-success' : 'status-danger' }}">
                    {{ $result['is_power_sufficient'] ? '✅ Мощность станка достаточна' : '❌ Мощность станка недостаточна' }}
                </div>
            </div>
        @endif
    </div>
@endsection
