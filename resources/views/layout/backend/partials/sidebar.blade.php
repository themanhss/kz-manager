<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel hidden">
            <div class="pull-left image">
                <img src="{{asset('/backend/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>Hoai Nam Tran</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <span class="hidden">
            @include('layout.backend.partials.searchbar')
        </span>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header hidden">MAIN NAVIGATION</li>
            <li class="hidden">
                <a href="{{url('admin/dashboard')}}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            <?php
            if (strpos(Route::getCurrentRoute()->getPath(), 'admin/promotions') !== false) {
                $active_promotion = true;
            }else{
                $active_promotion = false;
            }
            ?>

            <li class="{{$active_promotion?'active':'' }}">
                <a href="{{url('admin/promotions')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Promotions</span>
                </a>
            </li>

            @if($user->isSuperAdmin == 1)
                <?php
                if (strpos(Route::getCurrentRoute()->getPath(), 'admin/themes') !== false) {
                    $active_themes = true;
                }else{
                    $active_themes = false;
                }
                ?>
                <li class="{{$active_themes?'active':'' }}">
                    <a href="{{url('admin/themes')}}">
                        <i class="fa fa-cogs"></i>
                        <span>Themes</span>
                    </a>
                </li>
            @endif

            <?php
            if (strpos(Route::getCurrentRoute()->getPath(), 'admin/customers') !== false) {
                $active_customer = true;
            }else{
                $active_customer = false;
            }
            ?>
            <li class="{{$active_customer?'active':'' }}">
                <a href="{{url('admin/customers')}}">
                    <i class="fa fa-th"></i>
                    <span>Customers</span>
                </a>
            </li>
            <?php
            if (strpos(Route::getCurrentRoute()->getPath(), 'admin/leads') !== false) {
                $active_leads = true;
            }else{
                $active_leads = false;
            }
            ?>
            <li class="{{$active_leads?'active':'' }}">
                <a href="{{url('admin/leads')}}">
                    <i class="fa fa-share"></i>
                    <span>Enquiries</span>
                </a>
            </li>

            <?php
            if (strpos(Route::getCurrentRoute()->getPath(), 'admin/reports') !== false) {
                $active_reports = true;
            }else{
                $active_reports = false;
            }
            ?>
            <li class="{{$active_reports?'active':'' }}">
                <a href="{{url('admin/reports')}}">
                    <i class="fa fa-pie-chart"></i>
                    <span>Reports</span>
                </a>
            </li>
            <?php
            if (strpos(Route::getCurrentRoute()->getPath(), 'admin/users') !== false) {
                $active_user = true;
            }else{
                $active_user = false;
            }
            ?>
            <li class="{{$active_user?'active':''}}">
                <a href="{{url('admin/users')}}">
                    <i class="fa fa-fw fa-user"></i>
                    <span>Admin Users</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>