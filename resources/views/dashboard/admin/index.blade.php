@extends("layout.backend.master")

@section("custom-script")

    <script src="{{ asset('backend/plugins/flot/jquery.flot.min.js')}}" type="text/javascript"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    {{--<script src="{{ asset('backend/plugins/flot/jquery.flot.pie.min.js')}}" type="text/javascript"></script>--}}

    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
    <script src="{{ asset('backend/plugins/flot/jquery.flot.categories.min.js')}}" type="text/javascript"></script>

    <script src="{{ asset('backend/js/dashboard/dashboard.js')}}" type="text/javascript"></script>
@endsection

@section("content")
<!-- header-->
<section class="content-header hidden">
    <h1>
        Admin
        <small>Dashboard</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>
<!-- content -->
<section class="content">
    <input type="hidden" id="tocken" value="{{csrf_token()}}">
    <div class="row">
        <div class="col-lg-6 col-xs-6 dashboard-promotions">
            <h4>Hỗ Trợ Site Chính</h4>
            <h5><b>{{count($gmail_main)}} Gmail</b></h5>
            <h5><b>{{$blogger_main}} Blogger</b></h5>

        </div><!-- ./col -->

        <div class="col-lg-6 col-xs-6 dashboard-promotions">
            <h4>Hỗ Trợ Site Vệ Tinh</h4>
            <h5><b>{{count($gmail_vetinh)}} Gmail</b></h5>
            <h5><b>{{$blogger_vetinh}} Blogger</b></h5>
        </div><!-- ./col -->
    </div>

</section>
@stop
