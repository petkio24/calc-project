<div class="references-nav">
    <div class="nav-tabs">
        <div class="nav-tab">
            <a href="{{ route('references.index') }}" class="nav-tab-link {{ $activeTab === 'overview' ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Обзор
            </a>
        </div>
        <div class="nav-tab">
            <a href="{{ route('references.drilling-materials') }}" class="nav-tab-link {{ $activeTab === 'drilling' ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="2" x2="12" y2="6"></line>
                    <line x1="12" y1="18" x2="12" y2="22"></line>
                </svg>
                Сверление
            </a>
        </div>
        <div class="nav-tab">
            <a href="{{ route('references.turning-materials') }}" class="nav-tab-link {{ $activeTab === 'turning' ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="8"></circle>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                Точение
            </a>
        </div>
        <div class="nav-tab">
            <a href="{{ route('references.milling-materials') }}" class="nav-tab-link {{ $activeTab === 'milling' ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                    <line x1="3" y1="9" x2="21" y2="9"></line>
                    <line x1="3" y1="15" x2="21" y2="15"></line>
                </svg>
                Фрезерование
            </a>
        </div>
        <div class="nav-tab">
            <a href="{{ route('references.tool-materials') }}" class="nav-tab-link {{ $activeTab === 'tools' ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
                Инструменты
            </a>
        </div>
        <div class="nav-tab">
            <a href="{{ route('references.machine-types') }}" class="nav-tab-link {{ $activeTab === 'machines' ? 'active' : '' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
                Станки
            </a>
        </div>
    </div>
</div>
