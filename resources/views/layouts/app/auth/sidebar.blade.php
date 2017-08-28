@php
    /**
     * @var string[] $sectionNames
     * @var string $sectionName
     * @var \App\ValueObjects\Sidebar $sidebar
     */
@endphp

<div class="sidebar-wrapper">
    <div class="sidebar">
        <select class="form-control">
            @foreach ($sectionNames as $itSectionName)
                <option value="{{ $itSectionName }}" {{ $sectionName === $itSectionName ? 'selected' : '' }}>
                    {{ __(sprintf('common/sections.%s.name', $itSectionName)) }}
                </option>
            @endforeach
        </select>

        <hr>

        <div class="sidebar-menu panel-group">
            @each('layouts.app.auth.sidebar.items', $sidebar->getItems(), 'sidebarItem')
        </div>
    </div>
</div>