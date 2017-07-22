<div class="pull-right">
    <a data-confirm-message="{{ $message }}"
       data-confirm-url="{{ $url }}"
       data-confirm-method="{{ $urlMethod or 'delete' }}"
       class="btn btn-danger btn-confirm">
        <i class="fa fa-trash"></i>&nbsp;
        {{ __('components/form.buttons.delete') }}
    </a>
</div>