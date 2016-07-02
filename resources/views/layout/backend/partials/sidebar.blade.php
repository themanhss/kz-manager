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
            if (strpos(Route::getCurrentRoute()->getPath(), 'admin/gmails') !== false) {
                $active_gmails = true;
            }else{
                $active_gmails = false;
            }
            ?>

            <li class="{{$active_gmails?'active':'' }}">
                <a href="{{url('admin/gmails')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Google</span>
                </a>
            </li>

            <?php
            if (strpos(Route::getCurrentRoute()->getPath(), 'admin/blogs') !== false) {
                $active_blog = true;
            }else{
                $active_blog = false;
            }
            ?>
            <li class="{{$active_blog?'active':'' }}">
                <a href="{{url('admin/blogs')}}">
                    <i class="fa fa-th"></i>
                    <span>Blogs</span>
                </a>
            </li>
            <?php
            if (strpos(Route::getCurrentRoute()->getPath(), 'admin/blocks') !== false) {
                $active_crawler = true;
            }else{
                $active_crawler = false;
            }
            ?>
            <li class="{{$active_crawler?'active':'' }}">
                <a href="{{url('admin/blocks')}}">
                    <i class="fa fa-share"></i>
                    <span>Crawlers</span>
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