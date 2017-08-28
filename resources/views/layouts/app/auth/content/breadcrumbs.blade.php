@php
    /**
     * @var \App\ValueObjects\Breadcrumb[] $breadcrumbs
     */
@endphp

<div class="breadcrumbs">
    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            <li {{ /* @todo $breadcrumb->isActive() */ 0 ? 'active' : '' }}>
                @if ($breadcrumb->hasUrl())
                    <a href="{{ $breadcrumb->getUrl() }}/">
                        {{ $breadcrumb->getCaption() }}
                    </a>
                @else
                    {{ $breadcrumb->getCaption() }}
                @endif
            </li>
        @endforeach
    </ol>
</div>