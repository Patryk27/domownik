<div id="content">
    <div class="container-fluid">
        <div id="content-panel" class="panel panel-default">
            <div class="panel-heading">
                @include('layouts.application.auth.content.breadcrumbs', ['breadcrumbs' => Breadcrumb::getBreadcrumbs()])
            </div>

            @include('layouts.common.messages')

            <div class="panel-body">
                @if (array_key_exists('title', View::getSections()))
                    <fieldset>
                        <legend>
                            @yield('title')
                        </legend>
                    </fieldset>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</div>