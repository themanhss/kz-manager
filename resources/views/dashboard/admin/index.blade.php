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
            <h2>Last promotions &nbsp;&nbsp;&nbsp; <small><a class="underline" href="/admin/promotions">See
                        All</a></small></h2>
            <div class="box">
                <div class="box-body">
                    <table id="dataTable" class="table table-bordered table-striped dataTable" role="grid">
                        <thead>
                        <tr role="row">
                            <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Name</th>
                            <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                colspan="1">Start Date</th>
                            <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">End Date</th>
                            <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($promotions))
                            @foreach($promotions as $key=> $promotion)
                                <tr role="row" class="odd">
                                    <td><a class="underline"
                                           href="/admin/promotions/profile/{{$promotion->promotion_id}}">{{$promotion->name}}</a></td>
                                    <td>{{$promotion->start_date?date('d/m/Y',strtotime($promotion->start_date)):''}}</td>
                                    <td>{{$promotion->end_date?date('d/m/Y',strtotime($promotion->end_date)):''}}</td>
                                    <td>{{$promotion->status?'Active':'Pendding'}}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- ./col -->

        <div class="col-lg-6 col-xs-6 dashboard-promotions">
            <h2>
                Last Enquiries &nbsp;&nbsp;&nbsp;
                <small><a class="underline" href="{{url('admin/leads')}}">See All</a></small>
            </h2>
            <div class="box">
                <div class="box-body">
                    <table id="dataTable2" class="table table-bordered table-striped dataTable" role="grid">
                        <thead>
                        <tr role="row">
                            <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Customer</th>
                            <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                colspan="1">Promo</th>
                            <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Date</th>
                            <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Product</th>
                            <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Status</th>
                            <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">View</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($leads))
                            @foreach($leads as $key=> $lead)
                                <tr role="row" class="odd">
                                    <td>{{$lead->customer->first_name.' '.$lead->customer->last_name}}</td>
                                    @if($lead->promotion)
                                        <td>{{$lead->promotion->name}}</td>
                                    @else
                                        <td></td>
                                    @endif

                                    <td>{{$lead->date?date('d/m/Y',strtotime($lead->date)):''}}</td>
                                    <td>
                                        <?php $products = $lead->products ?>
                                        @if(!empty($products))
                                                @foreach($products as $product)
                                                    {{$product->name}}
                                                @endforeach
                                        @endif
                                    </td>
                                    <td>Active</td>
                                    @if($lead->promotion)
                                        <td><a href="{{ route('promotion.profile', ['id' => $lead->promotion->promotion_id]) }}" class="underline">VIEW</a></td>
                                    @else
                                        <td><a href="#" class="underline">VIEW</a></td>
                                    @endif

                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div><!-- ./col -->
    </div>

    {{--begin chart--}}
    <div class="row">
        <div class="col-md-6">
          {{--  <h2>
                Enquires per promotion
            </h2>--}}
            {{--<div class="box-chart" style="margin-top: 30px">
                <div id="donut-chart" style="height: 250px;"></div>
            </div>--}}
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-bar-chart-o"></i>
                    <h3 class="box-title">Enquires per promotion</h3>
                    {{--<div class="box-tools pull-right">--}}
                        {{--<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>--}}
                        {{--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>--}}
                    {{--</div>--}}
                </div>
                <div class="box-body">
                    <div id="bar-chart" style="height: 300px;"></div>
                </div><!-- /.box-body-->
            </div><!-- /.box -->
        </div>
    </div>
</section>
@stop
