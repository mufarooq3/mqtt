

@include('layout.header')

@include('layout.sidebar')
<link rel="stylesheet" href="{{url()}}/css/bootstrap-multiselect.css" type="text/css" />
<link rel="stylesheet" href="{{url()}}/css/on-off.css" type="text/css">
<script type="text/javascript" src="{{url()}}/js/bootstrap-multiselect.js"></script>
<style type="text/css">
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

		/*$("#checkbox_div input:radio").click(function() {

		if($(this).val() == 'android'){
			$("#android_div").show();
			$("#iphone_div").hide();
		}
		if($(this).val() == 'iphone'){
			$("#iphone_div").show();
			$("#android_div").hide();
		}

	   }); */
		
		//btn_sent
$(document).on('click', '.btngrpsub', function () {
   
        $('.modal_noty').modal('show');
        return false;
      
});

$(document).on('click', '.btngrpsub', function () {
$('.modal_grp_view').modal('show');
        });
        $(document).on('click', '.btngrpsub', function () {
var a = [];
        var b = [];
        $('.chk:checkbox:checked').each(function (i, obj) {
a.push($(this).val());
        });
        $('.chkgrp:checkbox:checked').each(function (i, obj) {
b.push($(this).val());
        });
        $.ajax({
        url: '{{url()}}/users_ajax/addGroup',
                type: 'get',
                data: "user_ids=" + a + "&grp_ids=" + b,
                success: function (result) {
                $('.modal_grp_add').modal('hide');
                        notification("topright", "success", "fa fa-check-circle vd_green", "success", "Record successfully deleted");
                }
        });
        });
        $(document).on('click', '.grpadd', function () {
           
            $('.modal_grp_add').modal('show');
        });
        
        //$('.modal_noty').modal('show');
        $(document).on('click', '.btn_sent', function () {
            $('.chk:checkbox').each(function (i, obj) {
                $(this).prop('checked', false);
            });
             $(this).closest('tr').find('[type=checkbox]').prop('checked', true);
             $('.modal_noty').modal('show');
			 $('.showaction').show();
			 
         });
        
        
        $(document).on('change', '.drpact', function () {

var a = [];
        $('.chk:checkbox:checked').each(function (i, obj) {
//alert($(this).val());
a.push($(this).val());
        });
        var url = "";
        var type = "";
        if ($(this).val() == "del"){
			//notification("topright", "error", "fa fa-exclamation-circle vd_red", "error", "you can not delete in demo");
			//return false;
if (confirm("Are you sure want to delete?")) {
type = "del";
        url = '{{url()}}/users_ajax/delete';
        } else{
return false;
        }
}

if ($(this).val() == "not"){
$('.modal_noty').modal('show');
        return false;
        }

if ($(this).val() == "grp"){
$('.modal_grp').modal('show');
        return false;
        }

if ($(this).val() == "deact"){
	//notification("topright", "error", "fa fa-exclamation-circle vd_red", "error", "you can not change status in demo");
			//return false;
type = "deact";
        url = '{{url()}}/users_ajax/status/false';
        }

if ($(this).val() == "act"){
	
	//notification("topright", "error", "fa fa-exclamation-circle vd_red", "error", "you can not change status in demo");
			//return false;
type = "act";
        url = '{{url()}}/users_ajax/status/true';
        }
if(url == ''){
    
    return false;
}
$.ajax({
url: url,
        type: 'get',
        data: "ids=" + a,
        success: function (result) {
        if (type == "act"){
        $('.chk:checkbox:checked').each(function (i, obj) {
        $('#check' + $(this).val()).prop("checked", true);
        });
        }

        if (type == "deact"){
        $('.chk:checkbox:checked').each(function (i, obj) {
        $('#check' + $(this).val()).prop("checked", false);
        });
        }

        if (type == "del"){
        $('.chk:checkbox:checked').each(function (i, obj) {
        $(this).parent().parent().hide();
        });
        }
        notification("topright", "success", "fa fa-check-circle vd_green", "success", "Success");
        }
});
        });
        function imageIsLoaded(e) {
        $('#myImg').show();
                $('#myImg').attr('src', e.target.result);
        }

$("#uploadBtn").change(function () {
$("#add-file").val($("#uploadBtn").val());
        if (this.files && this.files[0]) {     var reader = new FileReader();
        reader.onload = imageIsLoaded;
        reader.readAsDataURL(this.files[0]);
        }
});
        $(document).on('click', '#ckbCheckAll', function () {
if ($(this).prop('checked') == true){
$('.showaction').show();
        } else if ($(this).prop('checked') == false){
$('.showaction').hide();
        }
$(".chk").prop('checked', $(this).prop('checked'));
        });
        $(document).on('click', '.chk', function () {
if ($('input.chk:checked').length > 0){
$('.showaction').show();
        } else{
$('.showaction').hide();
        }
});
        $(document).on('click', '.btnaction', function () {     var action = $(this).attr('data-original-title');
        var id = $(this).attr('id');
        if (action == 'edit' || action == "view") {
$.ajax({
type: 'post',
        url: 'admin/categories/edit',
        data: "id=" + id,
        success: function (data) {
        $("#confirm").modal("show");
                $("#response").html(data);
        }
});
        }
if (action == 'delete') {
$('#confirmdel')
        .modal('show', {backdrop: 'static', keyboard: false})
        .one('click', '#delete', function (e) {
        $.ajax({
        type: 'post',
                url: 'admin/categories/delete',
                data: "id=" + id,
                success: function () {

                $('.hiderow' + id).closest('tr').hide();
                        notification("topright", "success", "fa fa-check-circle vd_green", "success", "Record successfully deleted");
                }
        });
        });
        }
});
        $(document).on('click', '.send_push', function (e) {
e.preventDefault();
        var title = $('#title').val();
        var msg = $('#msg').val();
        var a = [];
        $('.chk:checkbox:checked').each(function (i, obj) {
a.push($(this).val());
        });
        var data = "title=" + title + "&msg=" + msg + "&to=" + a + "&_token=" + '{{ Session::token() }}';
        $.ajax({
        url: '{{ action('Admincontroller@send_noty') }}',
                type: 'post',
                data: data,
                success: function (result) {

                }
        });
        });
        $('#notytype').change(function () {
if ($(this).val() == 2 || $(this).val() == 3 || $(this).val() == 4) {
$(".mLink").fadeIn();
        } else {
$(".mLink").fadeOut();
        }
if ($(this).val() == 1) {
$(".imgupload").fadeOut();
        $(".mMessage").fadeIn();
        $(".mEmotion").fadeOut();
        $(".dialoge_mokup").fadeOut();
        $(".simple_mokup").fadeIn();
        $(".webview_mokup").fadeOut();
        $(".news_mokup").fadeOut();
        } else if ($(this).val() == 3) {
$(".imgupload").fadeOut();
        $(".mMessage").fadeIn();
        $(".mEmotion").fadeOut();
        $(".webview_mokup").fadeIn();
        $(".simple_mokup").fadeOut();
        $(".dialoge_mokup").fadeOut();
        $(".news_mokup").fadeOut();
        } else if ($(this).val() == 2) {
$(".mMessage").fadeOut();
        $(".mEmotion").fadeOut();
        $(".imgupload").fadeIn();
        $(".webview_mokup").fadeOut();
        $(".simple_mokup").fadeOut();
        $(".dialoge_mokup").fadeIn();
        $(".news_mokup").fadeOut();
		notification("topright", "error", "fa fa-exclamation-circle vd_red", "error", "You can not send image notification to IOS devices");
        } else if ($(this).val() == 4) {
$(".mMessage").fadeOut();
        $(".mEmotion").fadeOut();
        $(".imgupload").fadeIn();
        $(".webview_mokup").fadeOut();
        $(".simple_mokup").fadeOut();
        $(".dialoge_mokup").fadeOut();
        $(".news_mokup").fadeIn();
        }
});
        $(document).on("click", ".btnsendnoty", function (e) {
        e.preventDefault();
        $(this).attr("disable", "true");
        var data = new FormData($(this).parents('form')[0]);
        var a = [];
        $('.chk:checkbox:checked').each(function (i, obj) {
            a.push($(this).val());
        });
//        document.getElementById('to').value=JSON.stringify(a);
//        console.log(document.getElementById('to').value);
//        $('#frmsubmit').submit();
        data.append("to", a);
        $.ajax({
        url: '{{ action('MqttController@send_noty') }}',
                type: 'post',
                data: data,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (result) {
                    $(".btnsendnoty").attr("disable", "false");
                    notification("topright", "success", "fa fa-check-circle vd_green", "success", "Notification Successfully Sent");
                    $('.modal_noty').modal('hide');
                },
                error: function(result){
//                    $(".btnsendnoty").attr("disable", "false");
//                    notification("topright", "success", "fa fa-check-circle vd_green", "success", result);
//                    $('.modal_noty').modal('hide');
                }
        })
        });
        $(document).on("click", ".check12", function () {
			//notification("topright", "error", "fa fa-exclamation-circle vd_red", "error", "you can not change status in demo");
			//return false;
			
			
var ida = $(this).parent().attr('id');
        var url = '';
        if ($(this).prop('checked') == true) {
url = '{{url()}}/users_ajax/status/true';
}
else if ($(this).prop('checked') == false) {
url = '{{url()}}/users_ajax/status/false';
}
$.ajax({
url: url,
        type: 'get',
        data: 'ids=' + ida,
        success: function (result) {
        notification("topright", "success", "fa fa-check-circle vd_green", "success", "Status successfully changed");
        }
});
});
        });</script>


<div class="example-modal">
    <div class="modal modal_noty">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="panel widget light-widget">
                    <div class="modal-body" style="max-width:1000px;width:950px;margin-left:-170px;display: inline-flex">
                        <div style="width:650px;">
                            <form id="frmsubmit" enctype="multipart/form-data" method="post" action="{{action('MqttController@send_noty')}}">
                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                {{--<input type="hidden" name="to" id="to" value="">--}}
                                
                                        <div id="android_div" class="form-group">
                                            <select class="form-control selectpicker span12" name="type" required="true" id="notytype">
                                                <option value="1">Simple Notification</option>
                                                <option value="2">Image Notification</option>
                                                <option value="3">Web Activity</option>
                                                <option value="4">News [saved in phone]</option>
                                             </select> 
                                        </div>
										

                                <label>Title</label>
                                <input class="form-control" type="text" value="" id="title" name="title"/>

                                <label>Message</label>
                                <textarea class="form-control" id="msg" name="msg" ></textarea>

                                <!--                                <div class="mEmotion" style="display: none">
                                                                    <label>Emotion</label>
                                                                    <input class="form-control" type="text" value="" placeholder="Emotion Eg. :)" id="title" name="emotion"/>
                                                                </div>-->

                                <div class="sec imgupload" style="display: none">
                                    <br/><label>Choose Image</label><br/>
                                    <input style="width:40%" id="add-file" placeholder="Choose File" disabled="disabled" />
                                    <div class="fileUpload btn btn-primary">
                                        <span>Upload Photo</span>
                                        <input  id="uploadBtn" type="file"  name="image"  name="image" class="upload" />
                                    </div>
                                    <br/>
                                    <img id="myImg" style="max-width: 435px;max-height:250px" src="" alt="your image" />
                                    <h3><b>OR</b></h3>
                                    <label>Direct Image Link</label><br/>
                                    <input type="text" name="image_path" value="" class="form-control" />
                                </div>

                                <div class="mLink" style="display:none">
                                    <div class="input-prepend  span12">
                                        <label>Link (http://)</label>
                                        <input id="prependedInput"  class="span10 black" name="link" placeholder="Link to be open" type="text"/>
                                    </div>
                                </div>
                                <hr>
                                <div class="row-fluid">
                                    <button type="submit" id="btn-loading" class="btn btn-primary btnsendnoty" data-loading-text="Loading...">Send Now</button>
                                </div>
                            </form>
                        </div>
                        <div style="margin-left:30px">
                            <div class="simple_mokup offset1" style="width:250px;">
                                <!--<div class="pTitle" style="position: absolute;top: 101px;left: 749px;color: #FFF;width:250px">Title</div>
                                <div class="pMsg" style="position: absolute;top: 123px;left: 639px;color: #FFF;width:250px">Message</div>-->
                                <img src="{{url()}}/assets/images/simple.png"/>
                            </div>
                            <div class="dialoge_mokup offset1" style="display: none;width:250px;">
                              <!--  <div class="pTitle" style="position: absolute;top: 235px;left: 705px;color: #000;width: 125px;border-bottom: #AAAAAA 1px solid;">Title</div>
                                <div class="pMsg" style="position: absolute;top: 259px;left: 705px;color: #000;width: 250px;height:70px">Message</div>
                                <div class="pEmo" style="position: absolute;top: 269px;left: 871px;color: #000;font-size: 20px"> :)</div> -->
                                <img src="{{url()}}/assets/images/dialog_push.png"/>
                            </div>
                            <div class="webview_mokup offset1" style="display: none;width:250px;">
                                <img src="{{url()}}/assets/images/webview.png"/>
                            </div>
							<div class="news_mokup offset1" style="display: none;">
                                        <img src="{{url()}}/assets/images/news_push.png"/>
                                    </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div><!-- /.example-modal -->

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
                            <div  class="panel-body table-responsive">
                                <div class="col-md-12">
                                    <form action="{{ action('Admincontroller@users_search') }}" method="get">
                                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                       <div class="col-md-2">
                                            <label>Device</label>
                                            <select class="form-control" name="device">
												<option <?php echo isset($data['device']) ? $data['device'] == 'both' ? 'selected' : '' : ''; ?>  value="both">Both</option>
                                                <option <?php echo!empty($data['device']) ? $data['device'] == 'android' ? 'selected' : '' : ''; ?>  value="android">Android</option>
                                                <option <?php echo isset($data['device']) ? $data['device'] == 'iphone' ? 'selected' : '' : ''; ?>  value="iphone">iPhone</option>
                                            </select>
                                        </div>
										
										<div class="col-md-3">
                                            <label>email</label>
                                            <input class="form-control" type="text" value="<?php echo!empty($data['email']) ? $data['email'] : ''; ?>" name="email"/>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <label>Status</label>
                                            <select class="form-control" name="is_active">
                                                <option value="">Select</option>
                                                <option <?php echo!empty($data['is_active']) ? $data['is_active'] == '1' ? 'selected' : '' : ''; ?>  value="1">Active</option>
                                                <option <?php echo isset($data['is_active']) ? $data['is_active'] == '0' ? 'selected' : '' : ''; ?>  value="2">DeActive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1" style="margin-top:28px">
                                            <input type="submit" value="search" class="btn btn-success"/>
                                        </div>
                                        @if(!empty($data['search']))
                                        <div class="col-md-1" style="margin-top:29px;width:105px;margin-left:14px">
                                            <a href="{{url()}}/users/" style="color: red">Clear search</a>
                                        </div>
                                        @endif
                                        <div class="col-md-3 showaction" style="display: none;margin-top:29px;margin-left:15px">
                                            <select class="form-control drpact">
                                                <option>Choose Action</option>
                                                <option value="del">Delete Users</option>
                                                <option value="not">Send Notification</option>
                                                <!--<option value="grp">Add to group</option>-->
                                                <option value="deact">Deactive users</option>
                                                <option value="act">Active users</option>
                                            </select>
                                        </div>
                                    </form>
                                </div>

                            </div>
                            
							<div class="panel-heading vd_bg-grey" >
                                <h3 class="panel-title" style="color:white"> <span class="menu-icon"> <i class="fa fa-dot-circle-o"></i> </span> Users </h3>
                            </div>
                            
							<div  class="panel-body table-responsive">
                                <table id="example" class="table table-hover display">
                                    <thead>
                                        <tr>
                                            <!--<th>#</th>-->
                                            <th style="width:5%"><input type="checkbox" value="" id='ckbCheckAll'/></th>
                                            <th style="width:25%">Name</th>
											<th style="width:25%">Wing Acc</th>
											<th style="width:25%">Phone</th>
											<th style="width:15%">Category</th>
											<th style="width:15%">Location</th>
                                            <th style="width:10%">Active</th>
                                            <th style="width:20%">notification</th>
                                        </tr>
                                        @foreach ($users as $user)
										<tr>
                                            <td style="width:5%"><input type="checkbox" value="{{$user->email}}" class="chk" name="send_noty"/></td>
                                            <td style="width:25%">{{ $user->username }}<br/><b style="font-size:10"></b></td>
{{--											<td style="width:25%">{{ $user->device_api == 'IOS'?$user->device_os: 'Android_'.$user->device_os }}</td>--}}
											<td style="width:25%">{{$user->wing_acc}}</td>
											<td style="width:25%">{{ $user->phone}}</td>
											<td style="width:25%">{{ $user->category }}</td>
											<?php 
											/*$now = time(); // or your date as well
											$your_date = strtotime($user->time);
											$datediff = $now - $your_date;*/
											// Create two new DateTime-objects...
											$date1 = new DateTime();
											$date2 = new DateTime($user->time);

											// The diff-methods returns a new DateInterval-object...
											$diff = $date2->diff($date1);
															// Call the format method on the DateInterval-object
											?>
                                            <td>
												<a href="https://maps.google.com/?q={{$user->last_lat}},{{$user->last_long}}" target="_blank">{{$user->last_lat}},{{$user->last_long}}<br/><b style="font-size:10;color:black"><?php echo 'Last update on ' .$diff->format('%a Day and %h hours').' ago'; ?></b></a>
                                            </td>
                                            <td style="width:10%">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label id="{{$user->uid}}" class="switch-new">
                                                            <input  type="checkbox" name="multi_sel" id="check{{$user->uid}}" <?php echo $user->is_active == 1 ? "checked" : '' ?>  class="switch-input check12">
                                                            <span class="switch-label" data-on="On" data-off="Off"></span>
                                                            <span class="switch-handle"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-success btn_sent" value="not" id="">send noty</button>
                                            </td>       
                                        </tr>
                                        @endforeach
                                    </thead>
								</table>
                                <div class="pagination">{!! $users->render() !!}</div>
                                <!--<div style="float: right;margin-top:35px"><     input type="button" value="send Push" class="open-modal btn btn-success" style="float:right;"/> </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layout.footer')