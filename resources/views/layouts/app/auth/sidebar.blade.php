@php
    /**
     * @var string[] $sectionNames
     * @var \App\ValueObjects\Sidebar $sidebar
     */
@endphp

<div id="sidebar-wrapper">
    <div id="sidebar">
        {{-- Section list --}}
        <select id="section-select" class="form-control">
            @foreach ($sectionNames as $sectionName)
                <option value="{{ $sectionName }}">
                    {{ __(sprintf('common/sections.%s.name', $sectionName)) }}
                </option>
            @endforeach
        </select>

        <hr>

        {{-- Current section's sidebar links --}}
        <div class="panel-group" id="sidebar-menu">
            @each('layouts.app.auth.sidebar.items', $sidebar->getItems(), 'sidebarItem')
        </div>
    </div>
</div>