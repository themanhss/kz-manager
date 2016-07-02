<header class="main-header">
    <!-- Logo -->
    <a href="/admin" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">Kz</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Kz Manager</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle toggle-header" data-toggle="dropdown">
                        @if(!empty($user->avatar))
                            <img src="{{asset('/uploads/avatar/'.$user->avatar)}}" class="img-circle img-header" alt="User Image" />
                        @else
                            <img src="{{asset('/backend/dist/img/default-user.png')}}" class="img-circle img-header" alt="User Image" />
                        @endif
                        {{--<span class="hidden-xs">Alexander Pierce</span>--}}
                        <span class="hidden-xs">{{ $user->firstName.' '.$user->lastName }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            @if(!empty($user->avatar))
                                <img src="{{asset('/uploads/avatar/'.$user->avatar)}}" class="img-circle" alt="User Image" />
                            @else
                                <img src="{{asset('/backend/dist/img/default-user.png')}}" class="img-circle" alt="User Image" />
                            @endif

                            <p>
                                {{$user->firstName.' '.$user->lastName}}
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="/admin/users/edit/{{$user->id}}" class="btn btn-default btn-flat">Edit Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="/auth/logout" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>