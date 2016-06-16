@extends("layout.backend.master")

@section("custom-script")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js" type="text/javascript"></script>
    <script src="{{asset('backend/plugins/daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('backend/plugins/input-mask/jquery.inputmask.js')}}" type="text/javascript"></script>
    <script src="{{asset('backend/plugins/input-mask/jquery.inputmask.date.extensions.js')}}"
            type="text/javascript"></script>
    <script src="{{ asset('backend/js/promotions/campaign.js')}}" type="text/javascript"></script>
@endsection


@section("content")
    <section class="content-header">
        <h1>
            SMS Marketing Campaign
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/promotions">Promotions</a></li>
            <li class="active">Create New SMS</li>
        </ol>
    </section>
    <div class="create-user">
        <div class="col-md-12 form-horizontal mt40">
            <input id="campaign_id" type="hidden" value="{{ $campaign->campaign_id }}">
            <fieldset>
                <div class="col-md-8 left-box">
                    <div class="row">
                        <input type="hidden" id="type" value="SMS Send">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="assetType">Campaign Name</label>

                            <div class="col-md-8">
                                <input id="name" name="name" type="text" class="form-control" disabled
                                       value="{{ $campaign->name  }}">
                                <span class="text-danger" id="error_name">{{ $errors->first('name') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="sms_text">SMS To Send</label>

                            <div class="col-md-8">
                                <textarea id="sms_text" name="sms_text" class="form-control"
                                          disabled>{{ $campaign->sms_text }}</textarea>
                                <span class="text-danger">{{ $errors->first('sms_text') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                {{--end.left-box--}}
                <div class="clearfix"></div>

                <div class="col-md-12 mt40">
                    <div class="box-filter">
                        <div class="form-group">
                            <label class="col-md-1 control-label">State</label>

                            <div class="col-md-2">
                                <select name="state" class="form-control" id="state">
                                    <option value="0">All country</option>
                                    <option value="AUSTRALIAN CAPITAL TERRITORY">AUSTRALIAN CAPITAL TERRITORY</option>
                                    <option value="NEW SOUTH WALES">NEW SOUTH WALES</option>
                                    <option value="NORTHEN TERRITORY">NORTHEN TERRITORY</option>
                                    <option value="QUEENSLAND">QUEENSLAND</option>
                                    <option value="TASMANIA">TASMANIA</option>
                                    <option value="VICTORYA">VICTORYA</option>
                                    <option value="WESTERN AUSTRALIA">WESTERN AUSTRALIA</option>
                                </select>
                            </div>

                            <label class="col-md-2 control-label"> Created Date</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="reservationtime" value="">
                                </div>
                            </div>


                        </div>

                    </div>
                    <div class="box">
                        <div class="box-body">
                            <table id="customerData" class="table table-bordered table-striped dataTable" role="grid">
                                <thead>
                                <tr role="row">
                                    {{--<th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>--}}
                                    <th class="" tabindex="0" aria-controls="" rowspan="1" colspan="1">
                                        First Name
                                    </th>
                                    <th class="" tabindex="0" aria-controls="" rowspan="1" colspan="1">
                                        Last Name
                                    </th>
                                    <th class="" tabindex="0" aria-controls="" rowspan="1" colspan="1">
                                        Email
                                    </th>
                                    <th class="" tabindex="0" aria-controls="" rowspan="1" colspan="1">
                                        Phone
                                    </th>
                                    <th class="" tabindex="0" aria-controls="" rowspan="1" colspan="1">
                                        State
                                    </th>
                                    <th class="" tabindex="0" aria-controls="" rowspan="1" colspan="1">
                                        Suburb
                                    </th>
                                    <th class="" tabindex="0" aria-controls="" rowspan="1" colspan="1">
                                        Postcode
                                    </th>
                                    <th class="" tabindex="0" aria-controls="" rowspan="1" colspan="1">
                                        Creation Date
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="customerTable">
                                @if(!empty($campaign))
                                    @foreach($campaign->communications as $communication)
                                        <tr role="row" class="odd">
                                            {{--<td><input type="checkbox" id="id[]" name="id[]" value="{{$communication->client->id}}" /></td>--}}
                                            <td>{{$communication->client->first_name}}</td>
                                            <td>{{$communication->client->last_name}}</td>
                                            <td>{{$communication->client->email}}</td>
                                            <td>{{$communication->client->mobile_phone}}</td>
                                            <td>{{$communication->client->state}}</td>
                                            <td>{{$communication->client->suburb}}</td>
                                            <td>{{$communication->client->postcode}}</td>
                                            <td>{{$communication->client->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- Form actions -->
                <div class="form-group">
                    <div class="col-md-12 text-center">
                        <a href="/admin/promotions/profile/{{$campaign->promotion_id}}"
                           class="btn btn-primary btn-create btn-sm">Cancel</a>
                        {{--<button id="sentSMS" class="btn btn-primary btn-create btn-save btn-sm">Sent SMS</button>--}}
                    </div>
                </div>

            </fieldset>
        </div>
    </div>
@stop
