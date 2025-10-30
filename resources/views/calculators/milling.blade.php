@extends('layouts.app')

@section('title', 'Калькулятор фрезерования')

@section('styles')
    <link href="{{ asset('css/calculator_milling.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="calculator-container">
        <!-- Хлебные крошки -->
        <nav class="calc-breadcrumbs">
            <a href="{{ route('home') }}" class="calc-breadcrumbs__item">Главная</a>
            <span class="calc-breadcrumbs__separator">›</span>
            <span class="calc-breadcrumbs__item active">Калькулятор фрезерования</span>
        </nav>

        <!-- Заголовок -->
        <div class="calc-header">
            <h1>Расчет режимов фрезерования</h1>
            <h2>Оптимальные параметры фрезерной обработки</h2>
        </div>

        <!-- Форма калькулятора -->
        <form method="POST" action="{{ route('calculators.milling.calculate') }}">
            @csrf

            <div class="calc-grid">
                <!-- Параметры фрезы -->
                <div class="calc-section">
                    <h3 class="section-title">Параметры фрезы</h3>

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="cutter_diameter">Диаметр фрезы (мм)</label>
                        <input class="calc-input-group__input" id="cutter_diameter"
                               name="cutter_diameter" type="number" step="0.1" placeholder="Введите диаметр"
                               value="{{ old('cutter_diameter') }}" required>
                    </div>

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="number_of_teeth">Количество зубьев</label>
                        <input class="calc-input-group__input" id="number_of_teeth"
                               name="number_of_teeth" type="number" step="1" placeholder="Введите количество"
                               value="{{ old('number_of_teeth') }}" required min="1">
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

                <!-- Параметры обработки -->
                <div class="calc-section">
                    <h3 class="section-title">Параметры обработки</h3>

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="width_of_cut">Ширина фрезерования (мм)</label>
                        <input class="calc-input-group__input" id="width_of_cut"
                               name="width_of_cut" type="number" step="0.1" placeholder="Введите ширину"
                               value="{{ old('width_of_cut') }}" required>
                    </div>

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="depth_of_cut">Глубина резания (мм)</label>
                        <input class="calc-input-group__input" id="depth_of_cut"
                               name="depth_of_cut" type="number" step="0.1" placeholder="Введите глубину"
                               value="{{ old('depth_of_cut') }}" required>
                    </div>

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
                    <div class="results-subtitle">Оптимальные режимы фрезерной обработки</div>
                </div>

                <div class="results-grid">
                    <div class="result-card">
                        <div class="result-label">Материал</div>
                        <div class="result-value">{{ $result['material'] }}</div>
                    </div>

                    <div class="result-card">
                        <div class="result-label">Диаметр фрезы</div>
                        <div class="result-value">{{ $result['cutter_diameter'] }} мм</div>
                    </div>

                    <div class="result-card">
                        <div class="result-label">Количество зубьев</div>
                        <div class="result-value">{{ $result['number_of_teeth'] }}</div>
                    </div>

                    <div class="result-card">
                        <div class="result-label">Ширина фрезерования</div>
                        <div class="result-value">{{ $result['width_of_cut'] }} мм</div>
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
                        <div class="result-label">Подача на зуб</div>
                        <div class="result-value">{{ $result['feed_per_tooth'] }} мм/зуб</div>
                    </div>

                    <div class="result-card">
                        <div class="result-label">Минутная подача</div>
                        <div class="result-value">{{ $result['feed_rate'] }} мм/мин</div>
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
