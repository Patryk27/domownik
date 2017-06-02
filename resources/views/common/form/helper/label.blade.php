<div>
    @if ($label)
        <label class="control-label"
               for="{{ $id }}">
            {{ $label }}
        </label>
    @endif

    @if ($helpUrl)
        <a class="btn btn-xs btn-info help-button"
           href="{{ $helpUrl }}"
           target="_blank">
            <i class="fa fa-question"></i>
        </a>
    @endif
</div>