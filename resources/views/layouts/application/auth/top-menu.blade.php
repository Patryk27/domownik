<div id="navbar" class="navbar no-gutter navbar-default navbar-fixed-top">
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
            <ul class="nav navbar-nav navbar-right" style="text-align:right">
                <li>
                    <a href="{{ route('dashboard.user.logout') }}">
                        <i class="fa fa-sign-out"></i>&nbsp;
                        {{ __('layout.navbar.logout') }}
                    </a>
                </li>
            </ul>

            <form action="{{ route('dashboard.search.find') }}"
                  method="post"
                  class="navbar-form navbar-right">
                {!!
                   Form::textInput()
                        ->setIdAndName('top-search')
                        ->setPlaceholder(__('layout.navbar.search_with_dots'))
                 !!}

                <button type="submit" class="btn btn-default">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>