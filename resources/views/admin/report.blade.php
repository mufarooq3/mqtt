@include('layout.header')

@include('layout.sidebar')

<div class="vd_content-wrapper">
    <div class="vd_container">
        <div class="vd_content clearfix">
            <div class="vd_head-section clearfix">
                <div class="vd_panel-header">
                    <div class="vd_panel-menu hidden-sm hidden-xs" data-intro="<strong>Expand Control</strong><br/>To expand content page horizontally, vertically, or Both. If you just need one button just simply remove the other button code." data-step=5  data-position="left">
                        <div data-action="remove-navbar" data-original-title="Remove Navigation Bar Toggle" data-toggle="tooltip" data-placement="bottom" class="remove-navbar-button menu"> <i class="fa fa-arrows-h"></i> </div>
                        <div data-action="remove-header" data-original-title="Remove Top Menu Toggle" data-toggle="tooltip" data-placement="bottom" class="remove-header-button menu"> <i class="fa fa-arrows-v"></i> </div>
                        <div data-action="fullscreen" data-original-title="Remove Navigation Bar and Top Menu Toggle" data-toggle="tooltip" data-placement="bottom" class="fullscreen-button menu"> <i class="glyphicon glyphicon-fullscreen"></i> </div>
                    </div>
                </div>
            </div>
            <div class="vd_content-section clearfix">
                <div class="panel widget light-widget">
                    <div class="panel-body">
                        <div class="panel widget">
                            <div  class="panel-body table-responsive">
                                <div class="col-md-12">
                                    <div class="searchform">
                                        <form   class="form-horizontal" method="get" action="{{action('admincontroller@report')}}">
                                            <div class="form-group">
                                                <label class="col-sm-12">Date</label>
                                                <div class="col-sm-4">
                                                    <input name="start_date" class="form-control datepicker" placeholder="Start Date">
                                                </div>
                                                <div class="col-sm-4">
                                                    <input  name="end_date" class="form-control datepicker" placeholder="End Date">
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="submit" class="btn btn-success ">Search</button>
                                                </div>
                                            </div><!--/form-group-->

                                            <script>
                                                $( function() {
                                                    $( ".datepicker" ).datepicker();
                                                } );
                                            </script>

                                            <div class="form-group">
                                                <label class="col-sm-12">Status</label>
                                                <div class="col-sm-4">
                                                    <select name="status" id=""  class="form-control">
                                                        <option value="all" selected>All</option>
                                                        <option value="send">Sent</option>
                                                        <option value="pending">Pending</option>
                                                        {{--<option value="">Fail</option>--}}
                                                    </select>
                                                </div>

                                            </div><!--/form-group-->

                                        </form>
                                    </div>
                                </div>

                            </div>

                            {{--<div class="panel-heading vd_bg-grey" >--}}
                                {{--<h3 class="panel-title" style="color:white"> <span class="menu-icon"> <i class="fa fa-dot-circle-o"></i> </span> Report </h3>--}}
                            {{--</div>--}}

                            <div  class="panel-body">
                                <table id="datatable" class="table table-striped table-bordered dt-responsive" style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        {{--<th></th>--}}
                                        {{--<th style="width:5%"><input type="checkbox" value="" id='ckbCheckAll'/></th>--}}
                                        <th style="width:15%">Category</th>
                                        <th style="width:20%">Type</th>
                                        <th style="width:25%">Title</th>
                                        <th style="width:25%">Message</th>
                                        <th style="width:25%">End Date/Time</th>
                                        <th style="width:10%">Send</th>
                                        <th style="width:10%">Pending</th>
                                        {{--<th style="width:10%">Pending</th>--}}
                                        {{--<th style="width:10%">Fail</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($notifications as $noty)
                                            <tr>
                                                {{--<td style="width:5%"><input type="checkbox" value="" id='ckbCheckAll'/></td>--}}

                                                <td>{{$noty->title}}</td>
                                                <td>{{$noty->type}}</td>
                                                <td>{{$noty->message}}</td>
                                                <td>{{($noty->payload)->msg}}</td>
                                                <td>{{$noty->deletion_date}}</td>
                                                <td>{{$noty->delivered}}</td>
                                                <td>{{$noty->send}}</td>
                                                {{--<td>{{$noty->pending}}</td>--}}
                                                {{--<td>{{$noty->fail}}</td>--}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{--<div class="pagination">{!! $users->render() !!}</div>--}}
                                <!--<div style="float: right;margin-top:35px"><     input type="button" value="send Push" class="open-modal btn btn-success" style="float:right;"/> </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var handleDataTableButtons = function() {
            if ($("#datatable").length) {
                $("#datatable").DataTable({
                    dom: "Bfrtip",
                    buttons: [
                        {
                            extend: "copy",
                            className: "btn-sm"
                        },
                        {
                            extend: "csv",
                            className: "btn-sm"
                        },
                        {
                            extend: "excel",
                            className: "btn-sm"
                        },
                        {
                            extend: "pdfHtml5",
                            className: "btn-sm"
                        },
                        {
                            extend: "print",
                            className: "btn-sm"
                        },
                    ],
                    order: [[ 3, 'asc' ]],
                    columnDefs: [

                    ],
                    responsive: true
                });
            }
        };

        TableManageButtons = function() {
            "use strict";
            return {
                init: function() {
                    handleDataTableButtons();
                }
            };
        }();
        TableManageButtons.init();
    });
</script>

@include('layout.footer')