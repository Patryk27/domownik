<div class="form-group {{ $required ? 'required' : ''}}"
     data-control-id="{{ $id or '' }}"
     data-control-name="{{ $name or '' }}">
    <div class="checkbox">
        <label>
            <input type="checkbox"
                   @if ($id)
                   id="{{ $id }}"
                   @endif
                   @if ($name)
                   name="{{ $name }}"
                   @endif
                   @if ($value)
                   value="{{ $value }}"
                   @endif
                   @if ($required)
                   required
                   @endif
                   @if ($autofocus)
                   autofocus
                    @endif
            />

            @if ($label)
                {{ $label }}
            @endif
        </label>
    </div>

    @include('common.form.helper.help-block', [
        'helpBlockEnabled' => $helpBlockEnabled,
    ])
</div>