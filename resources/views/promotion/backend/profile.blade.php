@extends("layout.backend.master")

@section("custom-script")
    <script src="{{ asset('backend/js/promotions/profile.js')}}" type="text/javascript"></script>
@endsection

@section("content")
    <section class="content-header">
        <h1 class="hidden">
            Promotions
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a type="button" href="/admin/promotions/create" class="btn btn-primary"><i class="fa fa-plus-circle fa-1x
            "></i>&nbsp;&nbsp;Add a new Promotion</a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i>Admin</a></li>
            <li class="active">Promotion Information</li>
        </ol>
    </section>
    <div class="create-user profile-customer">

        <div class="col-md-12 form-horizontal ">

            <div class="form-group">
                <label class="col-md-1 control-label text-nowrap" for="assetType">Promo Name</label>

                <div class="col-md-3">
                    <input id="email" name="email" type="text" disabled class="form-control"
                           value="{{ $promotion->name }}">
                </div>

                <label class="col-md-1 col-md-offset-1 control-label text-nowrap" for="assetType">Status</label>

                <div class="col-md-3">
                    <select class="form-control" disabled id="status" name="status">
                        <option value="Finished" {{ $promotion->status == "Finished" ? 'selected' : '' }}>Finished
                        </option>
                        <option value="Ready to launch" {{ $promotion->status == "Ready to launch" ? 'selected' : '' }}>
                            Ready to launch
                        </option>
                        <option value="Draft" {{ $promotion->status == "Draft" ? 'selected' : '' }}>Draft</option>
                        <option value="Active" {{ $promotion->status == "Active" ? 'selected' : '' }}>Active</option>
                    </select>
                </div>

            </div>

            <div class="form-group">
                <label class="col-md-1 control-label text-nowrap" for="assetType">Starting Date</label>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" disabled name="start_date" class="form-control"
                               data-inputmask="'alias':'dd/mm/yyyy'"
                               value="{{date('d/m/Y', strtotime($promotion->start_date))}}"
                               data-mask/>
                    </div><!-- /.input group -->
                </div>

                <label class="col-md-1 col-md-offset-1 control-label" for="assetType">End Date</label>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" disabled name="start_date" class="form-control"
                               data-inputmask="'alias':'dd/mm/yyyy'"
                               value="{{date('d/m/Y', strtotime($promotion->end_date))}}"
                               data-mask/>
                    </div><!-- /.input group -->
                </div>


            </div>

            <div class="form-group">
                <label class="col-md-1 control-label" for="assetType">Theme</label>

                <div class="col-md-3">
                    <select class="form-control" disabled id="theme_id" name="theme_id">
                        <option value='0'>Select Theme</option>
                        @if(!empty($themes))
                            @foreach($themes as $key=>$theme)
                                <option value="{{$theme->theme_id}}" {{ $promotion->theme_id == $theme->theme_id ? 'selected' : '' }}>{{$theme->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <label class="col-md-1 col-md-offset-1 control-label" for="assetType">Promotion Key</label>

                <div class="col-md-3">
                    <input type="text" disabled name="promotion_key" class="form-control"
                           value="{{ $promotion->promotion_key }}"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-1 control-label" for="assetType">Description</label>

                <div class="col-md-8">
                    <textarea id="description" disabled rows="10" name="description" type="text" class="form-control"
                              value="{{ $promotion->description  }}">{{ $promotion->description  }}</textarea>
                </div>
            </div>

        </div>

        <div class="form-group">

            <div class="col-md-3">
                <a type="button" href="/admin/promotions/edit/{{$promotion->promotion_id}}" class="btn btn-primary"><i
                            class="fa fa-pencil
        fa-1x"></i>&nbsp;
                    &nbsp;Edit
                    Promotion
                </a>
            </div>
            <div class="col-md-3">
                <a type="button" href="/admin/promotions/{{$promotion->promotion_id}}/page/edit"
                   class="btn btn-primary"><i class="fa fa-pencil-square-o fa-1x"></i>&nbsp;&nbsp;Edit
                    Promotion Page Content
                </a>
            </div>

            <div class="col-md-12 form-horizontal">
                <div class="form-group">
                    <div class="col-md-6">
                        <h2>Products</h2>
                    </div>
                    <div class="col-md-6 box-add-communication">
                        <a type="button" href="/admin/promotions/{{$promotion->promotion_id}}/product/create" class="btn
                    btn-primary"><i class="fa fa-car
                    fa-1x"></i>&nbsp;&nbsp;
                            Create new product</a>
                    </div>
                </div>
                <div class="box">
                    <div class="box-body">
                        <table id="dataTable2" class="product_table table table-bordered table-striped dataTable"
                               role="grid">
                            <thead>
                            <tr role="row">
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Car Model
                                </th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Product Key</th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Status</th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Pricing</th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Edit Page
                                </th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Edit</th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $products = $promotion->products ?>
                            @if(!empty($products))
                                @foreach($products as $product)
                                    <tr role="row" class="odd" id="productRow{{$product->product_id}}">
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->product_key }}</td>
                                        <td>{{ $product->status?'Active':'Pending'}}</td>
                                        <td>
                                            <a href="/admin/promotions/{{$promotion->promotion_id}}/product/{{$product->product_id}}/pricing"
                                               class="underline uppercase">Edit Pricing</a></td>
                                        <td>
                                            <a href="/admin/promotions/{{$promotion->promotion_id}}/product/page/{{$product->product_id}}"
                                               class="underline uppercase">Edit product page content</a></td>
                                        <td>
                                            <a href="/admin/promotions/{{$promotion->promotion_id}}/product/edit/{{$product->product_id}}"
                                               class="underline uppercase">Edit</a></td>
                                        <td><a href="#" data-productname="{{$product->name}}"
                                               data-productid="{{$product->product_id}}"
                                               data-toggle="modal" data-target="#myModal"
                                               class="underline
                                    uppercase">Delete</a></td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            {{--End Products--}}

            <div class="col-md-12 form-horizontal">
                <div class="form-group">
                    <div class="col-md-6">
                        <h2>Promotion communications</h2>
                    </div>
                    <div class="col-md-6 box-add-communication">
                        <a type="button" href="/admin/promotions/{{$promotion->promotion_id}}/campaign/sms/create"
                           class="btn btn-primary"><i class="fa fa-mobile fa-1x"></i>&nbsp;&nbsp;
                            Send new SMS Campaign</a>&nbsp;&nbsp;
                        <a type="button" href="#" class="btn btn-primary"><i class="fa fa-paper-plane fa-1x"></i>&nbsp;&nbsp;
                            Send new Email Campaign</a>
                    </div>
                </div>
                <div class="box">
                    <div class="box-body">
                        <table id="dataTable" class="table table-bordered table-striped dataTable" role="grid">
                            <thead>
                            <tr role="row">
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">Communication Type
                                </th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Campaign
                                    Name
                                </th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Date</th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">View
                                    Details
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($campaigns))
                                @foreach($campaigns as $key=>$campaign)
                                    <tr role="row" class="odd">
                                        <td>{{$campaign->type}}</td>
                                        <td>{{$campaign->name}}</td>
                                        <td>{{date('d/m/Y', strtotime($campaign->send_date))}}</td>
                                        <td><a href="{{ route('campaign_show', ['campaign_id' => $campaign->campaign_id]) }}" class="underline">View</a></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            {{--End Promotion communications--}}

            <div class="col-md-12 form-horizontal">
                <div class="form-group">
                    <div class="col-md-6">
                        <h2>Promotion Leads</h2>
                    </div>
                    <div class="col-md-6 box-add-communication">
                        <a type="button" href="/admin/promotions/{{$promotion->promotion_id}}/lead/create"
                           class="btn btn-primary"><i class="fa fa-flag-checkered fa-1x"></i>&nbsp;&nbsp;Add New
                            Lead</a>
                    </div>
                </div>
                <div class="box">
                    <div class="box-body">
                        <table id="dataTable" class="table table-bordered table-striped dataTable" role="grid">
                            <thead>
                            <tr role="row">
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Client</th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">Communication Type
                                </th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Email
                                    Address
                                </th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Phone
                                    number
                                </th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Date</th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">View
                                    Details
                                </th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Edit</th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $leads = $promotion->leads ?>
                            @if(!empty($leads))
                                @foreach($leads as $lead)
                                    <tr role="row" class="odd" id="leadRow{{$lead->lead_id}}">
                                        <td>{{$lead->customer->first_name.' '.$lead->customer->last_name}}</td>
                                        <td>{{$lead->source_type}}</td>
                                        <td>{{$lead->email}}</td>
                                        <td>{{$lead->phone}}</td>
                                        <td>{{date('d/m/Y', strtotime($lead->date))}}</td>
                                        <td><a class="underline"
                                               href="/admin/promotions/{{$promotion->promotion_id}}/lead/{{$lead->lead_id}}/view">VIEW</a>
                                        </td>
                                        <td><a class="underline"
                                               href="/admin/promotions/{{$promotion->promotion_id}}/lead/edit/{{$lead->lead_id}}">EDIT</a>
                                        </td>
                                        <td><a class="underline" href="#"
                                               data-leadname="{{$lead->customer->first_name.' '.$lead->customer->last_name}}"
                                               data-leadid="{{$lead->lead_id}}" data-toggle="modal"
                                               data-target="#myModalLead">DELETE</a></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            {{--End Promotions Leads--}}
            <div class="clearfix"></div>

        {{--Confirm box--}}
        <!-- Confirm delete promotion product -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">IAG</h4>
                        </div>
                        <div class="modal-body">
                            Do you want to delete <b>"<span id="productName"></span>"</b> ?
                        </div>
                        <div class="modal-footer">
                            {{-- <form id="deleteForm" class="form-horizontal"
                                   action="/admin/promotions/{{$promotion->promotion_id}}/delete" method="post"
                                   enctype="multipart/form-data">--}}
                            <meta name="csrf-token" content="{{ csrf_token() }}"/>
                            <input type="hidden" name="product_id" value="" id="productId"/>
                            <input type="hidden" id="promotionId" value="{{$promotion->promotion_id}}"/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            <button type="button" id="deleteForm" class="btn btn-primary">Yes</button>


                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirm delete promotion lead -->
            <div class="modal fade" id="myModalLead" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="">IAG</h4>
                        </div>
                        <div class="modal-body">
                            Do you want to remove <b>"<span id="leadName"></span>"</b> ?
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="leadId" value="" id="leadId"/>
                            <input type="hidden" id="promotionId" value="{{$promotion->promotion_id}}"/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            <button type="button" id="deleteFormLead" class="btn btn-primary">Yes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@stop
