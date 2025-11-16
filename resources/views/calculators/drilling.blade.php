@extends('layouts.app')

@section('title', 'Профессиональный калькулятор сверления')

@section('styles')
    <link href="{{ asset('css/calculator_drilling.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="calculator-container">
        <!-- Хлебные крошки -->
        <nav class="calc-breadcrumbs">
            <a href="{{ route('home') }}" class="calc-breadcrumbs__item">Главная</a>
            <span class="calc-breadcrumbs__separator">›</span>
            <span class="calc-breadcrumbs__item active">Профессиональный калькулятор сверления</span>
        </nav>

        <!-- Заголовок -->
        <div class="calc-header">
            <h1>Профессиональный расчет режимов сверления</h1>
            <h2>Точные параметры для оптимальной сверлильной обработки</h2>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Форма калькулятора -->
        <form method="POST" action="{{ route('calculators.drilling.calculate') }}">
            @csrf

            <div class="calc-grid">
                <!-- Параметры заготовки -->
                <div class="calc-section">
                    <h3 class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        Параметры заготовки
                    </h3>

                    <!-- Материал заготовки -->
                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="material_id">Материал заготовки</label>
                        <div class="styled-select">
                            <select class="styled-select__input" id="material_id" name="material_id" required>
                                <option value="">Выберите материал заготовки</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}"
                                        {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                        {{ $material->name }} ({{ $material->hardness_range }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="diameter">Диаметр сверла (мм)</label>
                            <input class="calc-input-group__input" id="diameter"
                                   name="diameter" type="number" step="0.01" min="0.1" max="100"
                                   placeholder="Ø сверла" value="{{ old('diameter', 10) }}" required>
                        </div>

                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="hole_depth">Глубина отверстия (мм)</label>
                            <input class="calc-input-group__input" id="hole_depth"
                                   name="hole_depth" type="number" step="0.1" min="1"
                                   placeholder="Глубина" value="{{ old('hole_depth', 20) }}" required>
                        </div>
                    </div>
                </div>

                <!-- Параметры инструмента -->
                <div class="calc-section">
                    <h3 class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                        </svg>
                        Параметры инструмента
                    </h3>

                    <!-- Тип сверла -->
                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="tool_id">Тип сверла</label>
                        <div class="styled-select">
                            <select class="styled-select__input" id="tool_id" name="tool_id" required>
                                <option value="">Выберите тип сверла</option>
                                @foreach($tools as $tool)
                                    <option value="{{ $tool->id }}"
                                        {{ old('tool_id') == $tool->id ? 'selected' : '' }}>
                                        {{ $tool->name }} ({{ $tool->tool_type_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Дополнительные параметры -->
                    <div class="input-row">
                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="operation_type">Тип операции</label>
                            <div class="styled-select">
                                <select class="styled-select__input" id="operation_type" name="operation_type">
                                    <option value="roughing" {{ old('operation_type') == 'roughing' ? 'selected' : '' }}>⚒️ Черновая обработка</option>
                                    <option value="finishing" {{ old('operation_type') == 'finishing' ? 'selected' : '' }}>✨ Чистовая обработка</option>
                                </select>
                            </div>
                        </div>

                        <div class="calc-input-group">
                            <label class="calc-input-group__label" for="coolant_used">Охлаждение</label>
                            <div class="styled-select">
                                <select class="styled-select__input" id="coolant_used" name="coolant_used">
                                    <option value="1" {{ old('coolant_used', true) ? 'selected' : '' }}>💧 С охлаждением</option>
                                    <option value="0" {{ !old('coolant_used', true) ? 'selected' : '' }}>🌵 Без охлаждения</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Параметры станка -->
                <div class="calc-section">
                    <h3 class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                            <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                            <line x1="6" y1="6" x2="6" y2="18"></line>
                        </svg>
                        Параметры станка
                        <span class="optional-badge">необязательно</span>
                    </h3>

                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="machine_type_id">
                            Тип станка
                            <span class="optional-hint">(если не указан, будет использован стандартный)</span>
                        </label>
                        <div class="styled-select">
                            <select class="styled-select__input" id="machine_type_id" name="machine_type_id">
                                <option value="">Выберите тип станка (опционально)</option>
                                @foreach($machineTypes as $machine)
                                    <option value="{{ $machine->id }}"
                                        {{ old('machine_type_id') == $machine->id ? 'selected' : '' }}>
                                        {{ $machine->name }} ({{ $machine->power_range }}, до {{ $machine->max_rpm }} об/мин)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Состояние станка -->
                    <div class="calc-input-group">
                        <label class="calc-input-group__label" for="machine_age">
                            Состояние станка
                            <span class="optional-hint">(для корректировки режимов)</span>
                        </label>
                        <div class="styled-select">
                            <select class="styled-select__input" id="machine_age" name="machine_age">
                                <option value="new" {{ old('machine_age') == 'new' ? 'selected' : '' }}>🟢 Новый (0-2 года)</option>
                                <option value="normal" {{ old('machine_age', 'normal') == 'normal' ? 'selected' : '' }}>🟡 Нормальный (3-7 лет)</option>
                                <option value="worn" {{ old('machine_age') == 'worn' ? 'selected' : '' }}>🔴 Изношенный (8+ лет)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Кнопка расчета -->
            <div class="calc-actions-section">
                <div class="calc-actions">
                    <button type="submit" class="btn-calculate">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="7.5,4.21 12,6.81 16.5,4.21"></polyline>
                            <polyline points="7.5,19.79 7.5,14.6 3,12"></polyline>
                            <polyline points="21,12 16.5,14.6 16.5,19.79"></polyline>
                            <polyline points="3.27,6.96 12,12.01 20.73,6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        Рассчитать режимы сверления
                    </button>
                </div>
            </div>
        </form>

        <!-- Результаты -->
        @if(isset($result))
            <div class="calc-results">
                <div class="results-header">
                    <h3>Результаты профессионального расчета</h3>
                    <div class="results-subtitle">Оптимальные режимы сверлильной обработки</div>
                </div>

                <!-- Основные параметры -->
                <div class="results-section">
                    <h4 class="section-subtitle">Основные параметры</h4>
                    <div class="results-grid">
                        <div class="result-card">
                            <div class="result-label">Материал заготовки</div>
                            <div class="result-value">{{ $result['material'] }}</div>
                            <div class="result-info">{{ $result['material_hardness'] }}</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Тип сверла</div>
                            <div class="result-value">{{ $result['tool'] }}</div>
                            <div class="result-info">{{ $result['tool_type'] }}, {{ $result['tool_material'] }}</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Диаметр сверла</div>
                            <div class="result-value">{{ $result['diameter'] }} мм</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Глубина отверстия</div>
                            <div class="result-value">{{ $result['hole_depth'] }} мм</div>
                        </div>

                        <div class="result-card {{ $result['used_default_machine_type'] ? 'info' : '' }}">
                            <div class="result-label">Тип станка</div>
                            <div class="result-value">{{ $result['machine_type'] }}</div>
                            @if($result['used_default_machine_type'])
                                <div class="result-info">🔄 Использовано значение по умолчанию</div>
                            @endif
                        </div>

                        <div class="result-card {{ $result['machine_condition'] == 'worn' ? 'danger' : 'info' }}">
                            <div class="result-label">Состояние станка</div>
                            <div class="result-value">
                                @if($result['machine_condition'] == 'new') 🟢 Новый
                                @elseif($result['machine_condition'] == 'normal') 🟡 Нормальное
                                @else 🔴 Изношенный
                                @endif
                            </div>
                            <div class="result-info">Срок службы: {{ $result['years_in_service'] }} лет</div>
                        </div>
                    </div>
                </div>

                <!-- Режимы резания -->
                <div class="results-section">
                    <h4 class="section-subtitle">Режимы резания</h4>
                    <div class="results-grid">
                        <div class="result-card {{ $result['is_rpm_valid'] ? 'success' : 'danger' }}">
                            <div class="result-label">Обороты шпинделя</div>
                            <div class="result-value">{{ $result['spindle_rpm'] }} об/мин</div>
                            @if(!$result['is_rpm_valid'])
                                <div class="result-warning">⚠ Превышение максимальных оборотов станка</div>
                            @endif
                        </div>

                        <div class="result-card">
                            <div class="result-label">Скорость резания</div>
                            <div class="result-value">{{ $result['cutting_speed'] }} м/мин</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Подача на оборот</div>
                            <div class="result-value">{{ $result['feed_per_revolution'] }} мм/об</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Минутная подача</div>
                            <div class="result-value">{{ $result['feed_rate'] }} мм/мин</div>
                        </div>
                    </div>
                </div>

                <!-- Силовые параметры -->
                <div class="results-section">
                    <h4 class="section-subtitle">Силовые параметры</h4>
                    <div class="results-grid">
                        <div class="result-card">
                            <div class="result-label">Осевое усилие</div>
                            <div class="result-value">{{ $result['thrust_force'] }} Н</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Крутящий момент</div>
                            <div class="result-value">{{ $result['torque'] }} Н·м</div>
                        </div>

                        <div class="result-card {{ $result['is_power_valid'] ? 'success' : 'danger' }}">
                            <div class="result-label">Мощность резания</div>
                            <div class="result-value">{{ $result['cutting_power'] }} кВт</div>
                            @if(!$result['is_power_valid'])
                                <div class="result-warning">⚠ Превышение мощности станка</div>
                            @endif
                        </div>

                        <div class="result-card">
                            <div class="result-label">Эффективная мощность</div>
                            <div class="result-value">{{ $result['effective_power'] }} кВт</div>
                        </div>
                    </div>
                </div>

                <!-- Производительность -->
                <div class="results-section">
                    <h4 class="section-subtitle">Производительность</h4>
                    <div class="results-grid">
                        <div class="result-card">
                            <div class="result-label">Время обработки отверстия</div>
                            <div class="result-value">{{ $result['cutting_time_per_hole'] }} мин</div>
                        </div>

                        <div class="result-card">
                            <div class="result-label">Съем материала</div>
                            <div class="result-value">{{ $result['material_removal_rate'] }} см³/мин</div>
                        </div>

                        <div class="result-card info">
                            <div class="result-label">Тип операции</div>
                            <div class="result-value">
                                {{ $result['operation_type'] == 'roughing' ? '⚒️ Черновая' : '✨ Чистовая' }}
                            </div>
                        </div>

                        <div class="result-card info">
                            <div class="result-label">Охлаждение</div>
                            <div class="result-value">
                                {{ $result['coolant_used'] ? '💧 С охлаждением' : '🌵 Без охлаждения' }}
                            </div>
                        </div>

                        <div class="result-card info">
                            <div class="result-label">Корректировка режимов</div>
                            <div class="result-value">-{{ $result['condition_reduction_percent'] }}%</div>
                            <div class="result-info">Учтено снижение производительности</div>
                        </div>
                    </div>
                </div>

                <!-- Статус расчета -->
                <div class="calculation-status">
                    @if($result['is_calculations_valid'])
                        <div class="status-success">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <div>
                                <strong>Расчет выполнен успешно!</strong>
                                <p>Все параметры находятся в допустимых пределах</p>
                            </div>
                        </div>
                    @else
                        <div class="status-warning">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <div>
                                <strong>Требуется корректировка параметров!</strong>
                                <p>Некоторые параметры превышают возможности оборудования</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Валидация диаметра
            const diameterInput = document.getElementById('diameter');
            if (diameterInput) {
                diameterInput.addEventListener('change', function() {
                    const value = parseFloat(this.value);
                    if (value < 0.1) {
                        this.value = 0.1;
                    } else if (value > 100) {
                        this.value = 100;
                    }
                });
            }

            // Валидация глубины
            const depthInput = document.getElementById('hole_depth');
            if (depthInput) {
                depthInput.addEventListener('change', function() {
                    const value = parseFloat(this.value);
                    if (value < 1) {
                        this.value = 1;
                    }
                });
            }
        });
    </script>
@endsection
