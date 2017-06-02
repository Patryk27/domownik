<div class="form-group {{ $required ? 'required' : ''}}"
     data-control-id="{{ $id or '' }}"
     data-control-name="{{ $name or '' }}">
    @include('common/form/helper/label')

    <textarea class="form-control"
              @if ($id)
              id="{{ $id }}"
              @endif
              @if ($name)
              name="{{ $name }}"
              @endif
              @if ($placeholder)
              placeholder="{{ $placeholder }}"
            @endif
    >{{ $value }}</textarea>

    <div class="help-block with-errors"></div>
</div>