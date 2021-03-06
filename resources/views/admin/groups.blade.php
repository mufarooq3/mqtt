@include('layout.header')

@include('layout.sidebar')
<link rel="stylesheet" href="{{url()}}/css/on-off.css" type="text/css">
<style>
    .fileUpload {
        position: relative;
        overflow: hidden;
        margin: 10px;
    }
    .fileUpload input.upload {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        filter: alpha(opacity=0);
    }
</style>
<script>
    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    $(document).ready(function () {
        var msg = '{{Session::get("msg")}}';
        var type = '{{Session::get("type")}}';
        if (msg != "" && type != "") {
            if (type == "success") {
                var icon = "fa fa-check-circle vd_green";
            } else {
                var icon = "fa fa-exclamation-circle vd_red";
            }
            notification("topright", type, icon, type, msg);
<?php echo Session::set('msg', "") ?>;
<?php echo Session::set('type', "") ?>;
        }

        $(document).on('click', '.btnaction', function () {
            var action = $(this).attr('data-original-title');
            var id = $(this).attr('id');
            if (action == 'edit') {
                $.ajax({
                    type: 'get',
                    url: '{{url()}}/groups/edit',
                    data: "id=" + id,
                    success: function (data) {
                        $("#confirm").modal("show");
                        $("#response").html(data);
                        $("#page").val(getParameterByName('page'));
                    }
                });
            }
            if (action == 'delete') {
                if (confirm("Are you sure want to delete?")) {
                    $.ajax({
                        type: 'get',
                        url: '{{url()}}/groups/delete',
                        data: "id=" + id,
                        success: function () {
                            $('#hiderow' + id).hide();
                            notification("topright", "success", "fa fa-check-circle vd_green", "success", "Record successfully deleted");
                        }
                    });
                }
                return false;
            }
        });
        $(document).on("click", ".check12", function () {
            var ida = $(this).parent().attr('id');
            var url = '';
            if ($(this).prop('checked') == true) {
                url = '{{url()}}/groups/status/true';
            }
            else if ($(this).prop('checked') == false) {
                url = '{{url()}}/groups/status/false';
            }
            $.ajax({
                url: url,
                type: 'get',
                data: 'id=' + ida,
                success: function (result) {
                    notification("topright", "success", "fa fa-check-circle vd_green", "success", "Status successfully changed");
                }
            });
        });
    });

</script>
<div aria-hidden="true" role="dialog" tabindex="-1" class="modal fade" id="confirm" style="display: none;z-index: 2147483648">
    <div class="modal-dialog" id="response">

    </div>
</div>
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
                            <div class="panel-heading vd_bg-grey">
                                <h3 class="panel-title"> <span class="menu-icon"> <i class="fa fa-dot-circle-o"></i> </span> Add Group </h3>
                            </div>
                            <div  class="panel-body table-responsive left">
                                <form class="form-horizontal"  action="{{url()}}/groups/add" method="post" role="form" id="register-form">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <input type="hidden" name="page" id="page" value="<?php echo empty($data['page']) ? 1 : $data['page'] ?>" />
                                    <div class="form-group">
                                        <label class="control-label  col-md-2">Group Name<span class="vd_red">*</span></label>
                                        <div id="first-name-input-wrapper"  class="controls col-md-8">
                                            <input type="text" placeholder="Group Name"  class="width-120 required"  name="name" id="grp_name" required >
                                        </div>
                                    </div>

                                    <input type="hidden" value="1" name="is_active"/>
                                    <div class="form-group" style="margin-left:160px">
                                        <button class="btn vd_bg-green vd_white" type="submit" id="submit-register">Submit</button>
                                    </div>
                                </form>

                            </div>
                            <div class="panel-heading vd_bg-grey">
                                <h3 class="panel-title"> <span class="menu-icon"> <i class="fa fa-dot-circle-o"></i> </span> Categories </h3>
                            </div>
                            <div  class="panel-body table-responsive">
                                <table id="example" class="table table-hover display">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Category Name</th>
                                            <th>Last Update</th>
                                            <th>Is Active</th>
                                            <th>Edit / Delete</th>
                                        </tr>
                                        <?php
                                        if (!empty($results)) {
                                            empty($data['srno']) ? $srno = 1 : $srno = $data['srno'];
                                            foreach ($results as $grp) {
                                                ?>
                                                <tr id="hiderow{{$grp->id}}">
                                                    <td>{{$srno}}</td>
                                                    <td><a href="{{url()}}/groups/users/{{$grp->id}}">{{ $grp->name }}</a></td>
                                                    <td>{{ $grp->created_date }}</group/users/{{$grp->id}}td>
                                                    <td>
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label id="{{$grp->id}}" class="switch-new">
                                                                    <input  type="checkbox" name="multi_sel" id="check{{$grp->id}}" <?php echo $grp->is_active == 1 ? "checked" : '' ?> class="switch-input check12">
                                                                    <span class="switch-label" data-on="On" data-off="Off"></span>
                                                                    <span class="switch-handle"></span>
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td><a href="#" data-original-title="edit" class="btn btn-success small btnaction" id="{{$grp->id}}">Edit</a>&nbsp;&nbsp;&nbsp;<a href="#" data-original-title="delete" class="btn btn-danger small btnaction"  id="{{$grp->id}}" >Delete</a></td>
                                                </tr>
                                                <?php
                                                $srno++;
                                            }
                                        }
                                        ?>
                                    </thead>

                                </table>
                                <div class="pagination">{!! $results->render() !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layout.footer')