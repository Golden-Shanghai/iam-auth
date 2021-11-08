<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ admin_base_path('/') }}" class="logo"></a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">

        <div class="text_words">{{ config('admin.name') }} · 后台管理系统</div>

        <ul class="nav navbar-nav">
            {!! Admin::getNavbar()->render('left') !!}
        </ul>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

            {!! Admin::getNavbar()->render() !!}

            <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ Admin::user()->avatar }}" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ Admin::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ Admin::user()->avatar }}" class="img-circle" alt="User Image">
                            <p>
                                {{ Admin::user()->name }}
                                <small style="color: #FFF;">加入时间 {{ Admin::user()->created_at }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ admin_base_path('auth/setting') }}"
                                   class="btn btn-default btn-flat">{{ trans('admin.setting') }}</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ admin_base_path('auth/logout') }}"
                                   class="btn btn-default btn-flat">{{ trans('admin.logout') }}</a>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>

    </nav>
</header>
