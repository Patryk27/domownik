<div class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="{{ route('dashboard.index.index') }}" class="navbar-brand">
                {{ config('app.name') }}
            </a>

            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div id="navbar-main" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right text-right">
                <li>
                    <a href="{{ route('dashboard.auth.logout') }}">
                        <i class="fa fa-sign-out"></i>&nbsp;
                        {{ __('layout.navbar.logout') }}
                    </a>
                </li>
            </ul>

            {!! Form::open([
                'route' => 'dashboard.search.find',
                'method' => 'post',
                'class' => 'navbar-form navbar-right',
            ]) !!}
            {!! Form::text('top-search', null, ['class' => 'form-control', 'placeholder' => __('layout.navbar.search_with_dots')]) !!}

            <button class="btn btn-default">
                <i class="fa fa-search"></i>
            </button>
            {!! Form::close() !!}
        </div>
    </div>
</div>