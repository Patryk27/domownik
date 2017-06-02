<div id="sidebar-wrapper">
    <div id="sidebar">
        {{-- Modules list --}}
        <select id="module-select" title="" class="form-control">
            @foreach ($enabledModules as $enabledModule)
                <option
                        name="{{ $enabledModule->getName() }}"
                        {{ $enabledModule->is($activeModule) ? 'selected' : '' }}>
                    {{ Translation::getModuleName($enabledModule->getName()) }}
                </option>
            @endforeach
        </select>

        <hr>

        {{-- Module's sidebar links --}}
        <div class="panel-group" id="sidebar-menu">
            @each('layouts/auth/sidebar/items', $activeModule->getSidebar()->getItems(), 'sidebarItem')
        </div>
    </div>
</div>