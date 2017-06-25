<div id="content">
    <div class="container-fluid">
        <div id="breadcrumbs">
            @include('layouts.application.auth.content.breadcrumbs', ['breadcrumbs' => Breadcrumb::getBreadcrumbs()])
        </div>

        @include('layouts.common.messages')

        <div id="content-panel" class="panel panel-default">
            @if (array_key_exists('title', View::getSections()))
                <div class="panel-heading">
                    <h4>
                        @yield('title')
                    </h4>
                </div>
            @endif

            <div class="panel-body">
                @yield('content')
            </div>
        </div>
    </div>
</div>