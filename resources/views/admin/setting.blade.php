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
                                <h3 class="panel-title" style="color:white"> <span class="menu-icon"> <i class="fa fa-dot-circle-o"></i> </span>Change API key</h3>
                            </div>
                            <div  class="panel-body table-responsive left">
                                <form class="form-horizontal"  action="{{url()}}/settings/add" method="post" role="form" id="register-form">
                                    <div class="form-group">
                                        <label class="control-label  col-md-2">API key<span class="vd_red">*</span></label>
                                        <div id="first-name-input-wrapper"  class="controls col-md-8">
                                            <input type="text" placeholder="API key" value="<?php echo $data->api_key ?>" class="width-120 required"  name="api_key" id="api_key" required >
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="control-label  col-md-2">GOOGLE MAP API key<span class="vd_red">*</span></label>
                                        <div id="first-name-input-wrapper"  class="controls col-md-8">
                                            <input type="text" placeholder="GOOGLE MAP API key" value="<?php echo $data->google_api_key ?>" class="width-120 required"  name="google_api_key" id="google_api_key" required >
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:160px">
                                        <button class="btn vd_bg-green vd_white" type="submit" id="submit-register">Submit</button>
                                    </div>
                                </form>
                            </div>
							
							<div class="panel-heading vd_bg-grey">
                                <h3 class="panel-title" style="color:white"> <span class="menu-icon"> <i class="fa fa-dot-circle-o"></i> </span>Change Password</h3>
                            </div>
                            <div  class="panel-body table-responsive left">
                                <form class="form-horizontal"  action="{{url()}}/settings/add" method="post" role="form" id="register-form">
                                    <div class="form-group">
                                        <label class="control-label  col-md-2">Old Password<span class="vd_red">*</span></label>
                                        <div id="first-name-input-wrapper"  class="controls col-md-8">
                                            <input type="password" placeholder="Old Password" value="" class="width-120 required"  name="old_password" id="password" required >
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="control-label  col-md-2">New Password<span class="vd_red">*</span></label>
                                        <div id="first-name-input-wrapper"  class="controls col-md-8">
                                            <input type="password" placeholder="New Password" value="" class="width-120 required"  name="new_password" id="new_password" required >
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-left:160px">
                                        <button class="btn vd_bg-green vd_white" type="submit" id="submit-register">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layout.footer')