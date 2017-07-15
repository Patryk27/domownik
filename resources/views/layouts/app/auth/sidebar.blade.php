@php
    /**
     * @var string[] $sectionNames
     * @var string $sectionName
     * @var \App\ValueObjects\Sidebar $sidebar
     */
@endphp

<div id="sidebar-wrapper">
    <div id="sidebar">
        {{-- Section list --}}
        <select id="section-select" class="form-control">
            @foreach ($sectionNames as $itSectionName)
                <option value="{{ $itSectionName }}" {{ $sectionName === $itSectionName ? 'selected' : '' }}>
                    {{ __(sprintf('common/sections.%s.name', $itSectionName)) }}
                </option>
            @endforeach
        </select>

        <hr>

        {{-- Current section's sidebar --}}
        <div class="panel-group" id="sidebar-menu">
            @each('layouts.app.auth.sidebar.items', $sidebar->getItems(), 'sidebarItem')
        </div>
    </div>
</div>