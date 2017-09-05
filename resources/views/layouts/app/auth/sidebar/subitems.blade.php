@php
    /**
     * @var \App\ValueObjects\Sidebar\Item[] $sidebarItems
     */
@endphp

@if (!empty($sidebarItems))
    <ul class="list-group">
        @foreach ($sidebarItems as $sidebarItem)
            <li class="list-group-item">
                <a href="{{ $sidebarItem->getUrl() }}">
                    {{ $sidebarItem->getCaption() }}

                    <div class="icon">
                        @if ($sidebarItem->hasIcon())
                            <i class="{{ $sidebarItem->getIcon() }}"></i>
                        @endif
                    </div>
                </a>

                @if ($sidebarItem->hasBadge())
                    <span class="badge-label label label-primary">
                    {{ $sidebarItem->getBadge() }}
                </span>
                @endif

                @include('layouts.app.auth.sidebar.subitems', ['sidebarItems' => $sidebarItem->getVisibleSubitems(), 'sidebarItems'])
            </li>
        @endforeach
    </ul>
@endif