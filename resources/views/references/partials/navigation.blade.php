<div class="references-nav">
    <div class="nav-tabs">
        <div class="nav-tab">
            <a href="{{ route('references.index') }}"
               class="nav-tab-link {{ $activeTab === 'overview' ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Обзор
            </a>
        </div>

        <!-- Материалы -->
        <div class="nav-tab dropdown">
            <a href="#" class="nav-tab-link {{ in_array($activeTab, ['turning', 'drilling', 'milling']) ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Материалы
            </a>
            <div class="dropdown-menu">
                <a href="{{ route('references.turning-materials') }}" class="dropdown-item">Точение</a>
                <a href="{{ route('references.drilling-materials') }}" class="dropdown-item">Сверление</a>
                <a href="{{ route('references.milling-materials') }}" class="dropdown-item">Фрезерование</a>
            </div>
        </div>

        <!-- Инструменты -->
        <div class="nav-tab dropdown">
            <a href="#" class="nav-tab-link {{ $activeTab === 'tools' ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
                Инструменты
            </a>
            <div class="dropdown-menu">
                <a href="{{ route('references.tool-materials') }}" class="dropdown-item">Материалы инструмента</a>
                <a href="{{ route('references.tool-geometries') }}" class="dropdown-item">Маркировка</a>
                <a href="{{ route('references.drilling-tools') }}" class="dropdown-item">Сверла</a>
                <a href="{{ route('references.milling-tools') }}" class="dropdown-item">Фрезы</a>
            </div>
        </div>

        <!-- Станки -->
        <div class="nav-tab">
            <a href="{{ route('references.machine-types') }}"
               class="nav-tab-link {{ $activeTab === 'machines' ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
                Станки
            </a>
        </div>

        <!-- Поиск -->
        <div class="nav-tab">
            <a href="{{ route('references.search') }}"
               class="nav-tab-link {{ $activeTab === 'search' ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                Поиск
            </a>
        </div>
    </div>
</div>

<style>
    .dropdown {
        position: relative;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        min-width: 200px;
        z-index: 1000;
        margin-top: 4px;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }

    .dropdown-item {
        display: block;
        padding: 12px 16px;
        color: #374151;
        text-decoration: none;
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.2s;
    }

    .dropdown-item:hover {
        background: #f9fafb;
        color: #3b82f6;
    }

    .dropdown-item:last-child {
        border-bottom: none;
    }
</style>
