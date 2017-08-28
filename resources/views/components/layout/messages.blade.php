@if (session()->has('flash_notification.message'))
    <div class="message-container">
        <div class="alert alert-{{ session('flash_notification.level') }}">
            <button type="button" class="close" data-dismiss="alert">
                &times;
            </button>

            {{ session('flash_notification.message') }}
        </div>
    </div>
@endif

@if (isset($layoutMessages))
    <div class="message-container">
        @foreach ($layoutMessages as $layoutMessage)
            <div class="alert alert-{{ $layoutMessage['type'] }}">
                <button type="button" class="close" data-dismiss="alert">
                    &times;
                </button>

                {{ $layoutMessage['message'] }}
            </div>
        @endforeach
    </div>
@endif

@if (isset($errors) && count($errors) > 0)
    <div class="message-container">
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif