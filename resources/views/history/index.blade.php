@extends('layouts.app')
@section('title', 'История расчетов')

@section('content')
    <div class="history-container">
        <div class="history-header">
            <h1>История расчетов</h1>
        </div>

        <!-- Фильтры -->
        <div class="history-filters">
            <form method="GET" class="filter-form-history">
                <div class="filter-group-history">
                    <label>Тип операции:</label>
                    <select name="operation_type" onchange="this.form.submit()">
                        <option value="">Все операции</option>
                        <option value="turning" {{ $operationType == 'turning' ? 'selected' : '' }}>Точение</option>
                        <option value="milling" {{ $operationType == 'milling' ? 'selected' : '' }}>Фрезерование</option>
                        <option value="drilling" {{ $operationType == 'drilling' ? 'selected' : '' }}>Сверление</option>
                    </select>
                </div>

                <div class="filter-group-history">
                    <input type="text" name="search" placeholder="Поиск по названию..."
                           value="{{ $search }}" class="search-input">
                    <button type="submit" class="btn-search">🔍</button>
                </div>
            </form>
        </div>

        <!-- Список расчетов -->
        <div class="calculations-list">
            @forelse($calculations as $calculation)
                <div class="calculation-item {{ $calculation->is_favorite ? 'favorite' : '' }}">
                    <div class="calculation-header">
                        <h3>{{ $calculation->title }}</h3>
                        <div class="calculation-actions">
                            <a href="{{ route('history.show', $calculation->id) }}"
                               class="btn-icon" style="text-decoration: none">👁️</a>
                            <button class="btn-icon delete-btn"
                                    data-id="{{ $calculation->id }}">🗑️</button>
                        </div>
                    </div>

                    <div class="calculation-info">
                    <span class="operation-badge {{ $calculation->operation_type }}">
                        {{ $calculation->operation_type == 'turning' ? '🔄 Точение' :
                           ($calculation->operation_type == 'milling' ? '⚙️ Фрезерование' : '🔩 Сверление') }}
                    </span>
                        <span class="calculation-date">
                        {{ $calculation->created_at->format('d.m.Y H:i') }}
                    </span>
                    </div>

                    <div class="calculation-preview">
                        <div class="preview-params">
                            <strong>Параметры:</strong> {{ $calculation->summary }}
                        </div>

                        <div class="preview-results">
                            @php $quickResults = $calculation->quick_results; @endphp
                            @if($quickResults['speed'])
                                <span>Vc: {{ $quickResults['speed'] }} м/мин</span>
                                <span>n: {{ $quickResults['rpm'] }} об/мин</span>
                                <span>P: {{ $quickResults['power'] }} кВт</span>
                            @endif
                        </div>
                    </div>

                    @if($calculation->notes)
                        <div class="calculation-notes">
                            <strong>Заметки:</strong> {{ Str::limit($calculation->notes, 100) }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <h3>История расчетов пуста</h3>
                    <p>Выполните расчеты в калькуляторах, чтобы они появились здесь</p>
                    <div class="empty-actions">
                        <a href="{{ route('calculators.turning') }}" class="btn btn-primary">
                            Перейти к калькуляторам
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Пагинация -->
        @if($calculations->hasPages())
            <div class="pagination-container">
                {{ $calculations->links() }}
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Добавление/удаление из избранного
            document.querySelectorAll('.favorite-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const calculationId = this.dataset.id;
                    const isFavorite = this.dataset.favorite === '1';

                    fetch(`/history/${calculationId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            is_favorite: !isFavorite
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.dataset.favorite = isFavorite ? '0' : '1';
                                this.closest('.calculation-item').classList.toggle('favorite');
                                showNotification('Настройки сохранены', 'success');
                            }
                        });
                });
            });

            // Удаление расчета
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const calculationId = this.dataset.id;

                    if (confirm('Удалить этот расчет из истории?')) {
                        fetch(`/history/${calculationId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    this.closest('.calculation-item').remove();
                                    showNotification('Расчет удален', 'success');
                                }
                            });
                    }
                });
            });

            function showNotification(message, type) {
                // Реализация уведомлений
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;
                notification.textContent = message;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        });
    </script>
@endsection
