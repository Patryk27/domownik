<div class="form-group {{ $required ? 'required' : ''}}"
     data-control-id="{{ $id or '' }}"
     data-control-name="{{ $name or '' }}">

    @include('common.form.helper.label')

    <select class="form-control"
            @if ($multiple)
            multiple
            @endif
            @if ($id)
            id="{{ $id }}"
            @endif
            @if ($name)
            @if ($multiple)
            name="{{ $name }}[]"
            @else
            name="{{ $name }}"
            @endif
            @endif
    >
        @foreach ($items as $itemValue => $itemCaption)
            @php
                $isSelected = false;

                if (is_array($value)) {
                    $isSelected = in_array($itemValue, $value, false);
                } elseif (!is_null($value)) {
                    $isSelected = $itemValue == $value;
                }
            @endphp
            <option value="{{ $itemValue }}"
                    {{ $isSelected ? 'selected' : '' }}>
                {{ $itemCaption }}
            </option>
        @endforeach
    </select>

    @include('common.form.helper.help-block', [
        'helpBlockEnabled' => $helpBlockEnabled,
    ])
</div>