<?php
/**
 * @var \App\Modules\ScaffoldingContract\Module\Sidebar\Item $sidebarItem
 */
?>
@php ($panelId = 'sidebar-panel-' . str_replace('.', '-', $sidebarItem->getName()))

<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#sidebar-menu" href="#{{ $panelId }}">
                <div class="icon">
                    @if ($sidebarItem->hasIcon())
                        <i class="{{ $sidebarItem->getIcon() }}"></i>
                    @endif
                </div>

                {{ $sidebarItem->getCaption() }}
            </a>

            @if ($sidebarItem->hasBadge())
                <span class="badge-label label label-primary">
                    {{ $sidebarItem->getBadge() }}
                </span>
            @endif
        </h4>
    </div>

    <div id="{{ $panelId }}" class="panel-collapse collapse in">
        @include('layouts/auth/sidebar/subitems', ['sidebarItems' => $sidebarItem->getVisibleSubitems(), 'sidebarItems'])
    </div>
</div>