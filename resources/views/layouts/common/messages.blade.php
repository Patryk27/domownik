@if (session()->has('flash_notification.message'))
    <div id="flash-message-container">
        <div class="alert alert-{{ session('flash_notification.level') }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                &times;
            </button>

            {!! session('flash_notification.message') !!}
        </div>
    </div>
@endif

@if (isset($errors) && count($errors) > 0)
    <div id="session-message-container">
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif