<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin | Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.4 -->
        <link href="{{ asset('backend/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />    
        <!-- FontAwesome 4.3.0 -->
        <link href="{{ asset('backend/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" /> 
        <!-- DATA TABLES -->
        <link href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{{ asset('backend/dist/css/AdminLTE.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. Choose a skin from the css/skins 
             folder instead of downloading all of them to reduce the load. -->
        <link href="{{ asset('backend/dist/css/skins/_all-skins.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- iCheck -->
        <link href="{{ asset('backend/plugins/iCheck/flat/blue.css')}}" rel="stylesheet" type="text/css" />
        <!-- Morris chart -->
        <link href="{{ asset('backend/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
        <!-- jvectormap -->
        <link href="{{ asset('backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
        <!-- Date Picker -->
        <link href="{{ asset('backend/plugins/datepicker/datepicker3.css')}}" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link href="{{ asset('backend/plugins/daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="{{ asset('backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- common style -->
        <link href="{{ asset('backend/bootstrap/css/common.css')}}" rel="stylesheet" type="text/css" />
        <!-- custom style -->
        <link href="{{ asset('backend/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script type='text/javascript'>
            (function (d, t) {
                var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
                bh.type = 'text/javascript';
                bh.src = 'https://www.bugherd.com/sidebarv2.js?apikey=oeuxlakmtiko9czfyxybgw';
                s.parentNode.insertBefore(bh, s);
            })(document, 'script');
        </script>
    </head>
    <body class="skin-blue sidebar-mini">
        <div class="wrapper"> 
            @include('layout.backend.partials.nav')
            <!-- Left side column. contains the logo and sidebar -->
            @include('layout.backend.partials.sidebar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                @yield('content')
                <div class="clearfix"></div>
            </div><!-- /.content-wrapper -->
            <!-- Fotter site -->  
            {{--@include('layout.backend.partials.footer')--}}
            <!-- /.footer site -->  
            <!-- Control Sidebar -->      
            @include('layout.backend.partials.controlbar')
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
            <div class='control-sidebar-bg'></div>
        </div><!-- ./wrapper -->
        @include('layout.backend.partials.scripts')
        @yield('custom-script')
    </body>
</html>