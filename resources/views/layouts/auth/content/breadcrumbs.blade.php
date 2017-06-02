<?php
/**
 * @var \App\ValueObjects\Breadcrumb[] $breadcrumbs
 */
?>
<ol class="breadcrumb">
    @foreach ($breadcrumbs as $breadcrumb)
        <li {{ 0 ? 'active' : '' }}>
            @if ($breadcrumb->hasUrl())
                <a href="{{ $breadcrumb->getUrl() }}/">
                    {{ $breadcrumb->getName() }}
                </a>
            @else
                {{ $breadcrumb->getName() }}
            @endif
        </li>
    @endforeach
</ol>