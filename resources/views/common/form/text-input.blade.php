<div class="form-group {{ $required ? 'required' : ''}}"
     data-control-id="{{ $id or '' }}"
     data-control-name="{{ $name or '' }}">

    @include('common.form.helper.label')

    @php
        $useInputGroupClass = !empty($leftAddonIcon) || !empty($rightAddonIcon);
    @endphp

    <div class="{{ $useInputGroupClass ? 'input-group' : '' }}">
        @if ($leftAddonIcon)
            <span class="input-group-addon">
                <i class="{{ $leftAddonIcon }}"></i>
            </span>
        @endif

        <input type="text"
               class="form-control"
               @if ($id)
               id="{{ $id }}"
               @endif
               @if ($name)
               name="{{ $name }}"
               @endif
               @if ($value)
               value="{{ $value }}"
               @endif
               @if ($placeholder)
               placeholder="{{ $placeholder }}"
               @endif
               @if ($required)
               required
               @endif
               @if ($autofocus)
               autofocus
                @endif
        />

        @if ($rightAddonIcon)
            <span class="input-group-addon">
                <i class="{{ $rightAddonIcon }}"></i>
            </span>
        @endif
    </div>

    @include('common.form.helper.help-block', [
        'helpBlockEnabled' => $helpBlockEnabled,
    ])
</div>