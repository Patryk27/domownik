<div class="content">
    @include('layouts.app.auth.content.breadcrumbs', ['breadcrumbs' => Breadcrumb::getBreadcrumbs()])
    @include('components.layout.messages')

    @yield('content')
</div>