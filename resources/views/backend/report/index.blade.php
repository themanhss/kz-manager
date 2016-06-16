@extends("layout.backend.master")

@section("custom-script")
    <script src="{{ asset('backend/plugins/flot/jquery.flot.min.js')}}" type="text/javascript"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="{{ asset('backend/plugins/flot/jquery.flot.pie.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('backend/js/report/report.js')}}" type="text/javascript"></script>
@endsection

@section("content")
    <input type="hidden" id="tocken" value="{{csrf_token()}}">
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class="active"><a href="/admin/report">Report</a></li>
        </ol>
    </section>

    <div class="report-box content">
        <div class="row">
            <div class="col-md-5">
                <h3>Enquiries per Promotion</h3>
                <div class="clear_fix"></div>
                <div class="box-body">
                    <div id="donut-chart" style="height: 300px;"></div>
                </div><!-- /.box-body-->
                <ul class="list-enquiries">
                    @if(!empty($promotions_enquiries))
                        @foreach($promotions_enquiries as $promo)
                            @if($promo->total_leads > 0)
                                <li>Promotion <b>{{$promo->name}}</b> - {{$promo->total_leads}} enquiries</li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="col-md-7">
                <h3>Promotions</h3>
                <div class="clear_fix"></div>
                <div class="box">
                    <div class="box-body">
                        <table id="promotionsTable" class="table table-bordered table-striped dataTable" role="grid">
                            <thead>
                            <tr role="row">
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">Name</th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">Start Date</th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">End Date</th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(!empty($promotions))
                                    @foreach($promotions as $promotion)
                                        <tr role="row" class="odd">
                                            <td>{{$promotion->name}}</td>
                                            <td>{{$promotion->start_date?date('d/m/Y', strtotime($promotion->start_date)):'' }}</td>
                                            <td>{{$promotion->end_date?date('d/m/Y', strtotime($promotion->end_date)):'' }}</td>
                                            <td>{{$promotion->status}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <div class="box-enquiries">
            <div>
                <h3>Enquiries</h3>
            </div>
            <div class="box">
                <div class="box-body">
                <table id="enquiriesTable" class="table table-bordered table-striped dataTable" role="grid">
                    <thead>
                    <tr role="row">
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">Promotion</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">Client</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">Communication Type</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">Email Address</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">Phone Number</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">Date</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">View Details</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(!empty($leads))
                            @foreach($leads as $lead)
                                <tr role="row" class="odd">
                                    @if($lead->promotion)
                                        <td>{{$lead->promotion->name}}</td>
                                    @else
                                        <td></td>
                                    @endif

                                    <td>{{$lead->customer->first_name.' '.$lead->customer->last_name}}</td>
                                    <td>{{$lead->source_type}}</td>
                                    <td>{{$lead->email}}</td>
                                    <td>{{$lead->phone}}</td>
                                    <td>{{$lead->date?date('d/m/Y', strtotime($lead->date)):'' }}</td>
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
        </div>
        {{--End box reriquiries--}}

        <div class="box-campaign-send">
            <div>
                <h3>Campaign send</h3>
            </div>
            <div class="box">
                <div class="box-body">
                <table id="campaignTable" class="table table-bordered table-striped dataTable" role="grid">
                    <thead>
                    <tr role="row">
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">Promo</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">Communication Type</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">Campaign Name</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">Date</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">View Details</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(!empty($campaigns))
                            @foreach($campaigns as $campaign)
                                <tr role="row" class="odd">
                                    <td>{{$campaign->promotion->name}}</td>
                                    <td>{{$campaign->type}}</td>
                                    <td>{{$campaign->name}}</td>
                                    <td>{{$campaign->send_date?date('d/m/Y', strtotime($campaign->send_date)):'' }}</td>
                                    <td><a href="#" class="underline">VIEW</a></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            </div>
        </div>
        {{--End Campaign--}}

        <div class="box-campaign-send">
            <div>
                <h3>Most Active Users</h3>
            </div>
            <div class="box">
                <div class="box-body">
                <table id="customersTable" class="table table-bordered table-striped dataTable" role="grid">
                    <thead>
                    <tr role="row">
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">First Name</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">Last Name</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">Email</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">Pages visited</th>
                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                            colspan="1">View Details</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(!empty($customers))
                            @foreach($customers as $customer)
                                <tr role="row" class="odd">
                                    <td>{{$customer->first_name}}</td>
                                    <td>{{$customer->last_name}}</td>
                                    <td>{{$customer->email}}</td>
                                    <td>{{$customer->total_page}}</td>
                                    <td><a href="{{ url('customers/profile', ['id' => $customer->id]) }}" class="underline">VIEW</a></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            </div>
        </div>

    </div>

@stop
