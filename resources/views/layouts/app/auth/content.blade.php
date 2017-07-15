<div id="content">
    <div class="container-fluid">
        <div id="breadcrumbs">
            @include('layouts.app.auth.content.breadcrumbs', ['breadcrumbs' => Breadcrumb::getBreadcrumbs()])
        </div>

        @include('layouts.common.messages')

        <div id="page-content">
            @yield('content')
        </div>
    </div>
</div>