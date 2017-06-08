@if($icon)
    <i class="fa fa-spinner fa-spin"></i>
@endif

@if($label)
    {{ __('js/ajax.common.loading') }}
@endif