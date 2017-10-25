@include('layout.header')

@include('layout.sidebar')
<link rel="stylesheet" href="{{url()}}/css/bootstrap-multiselect.css" type="text/css" />
<link rel="stylesheet" href="{{url()}}/css/on-off.css" type="text/css">
<script type="text/javascript" src="{{url()}}/js/bootstrap-multiselect.js"></script>

<style>
    #map {
        height: 300px;
    }
    #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
    }

    #type-selector label {
        font-size: 13px;
        font-weight: 300;
    }

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
        filter: alpha(opacity = 0);
    }
</style>

<script type="text/javascript" src="{{url()}}/js/gritter.js"></script>
<script type="text/javascript" src="{{url()}}/js/mapswidget.js"></script>
<script type="text/javascript" src="{{url()}}/js/distancewidget.js"></script>
<script>

    function split(val) {
        return val.split(/,\s*/);
    }
    function extractLast(term) {
        return split(term).pop();
    }
    function imageIsLoaded(e) {
        $('#myImg').show();
        $('#myImg').attr('src', e.target.result);
    }
    $(document).ready(function () {



        $(document).on("click", ".check12", function () {
            if ($(this).prop('checked') == true) {
                $("#map_style").show();
                $("#multi_sel").val("on");
                $("#example-dropRight").val("");
            }
            else if ($(this).prop('checked') == false) {
                $("#map_style").hide();
                $("#multi_sel").val("off");
            }
        });
        $(document).on("change", "#uploadBtn", function () {
            $("#add-file").val($("#uploadBtn").val());
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
            }
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
            } else if ($(this).val() == 5) {
                $(".mMessage").fadeOut();
                $(".mEmotion").fadeOut();
                $(".imgupload").fadeIn();
                $(".webview_mokup").fadeOut();
                $(".simple_mokup").fadeOut();
                $(".dialoge_mokup").fadeIn();
                $(".news_mokup").fadeOut();
            }
        });
        $(document).on("click", ".btnsendnoty", function (e) {
            e.preventDefault();
            $(this).attr("disable", 'true');
            var data = new FormData($(this).parents('form')[0]);
            var a = [];
            $('.chk:checkbox:checked').each(function (i, obj) {
                a.push($(this).val());
            });
            data.append("to", a);

            $.ajax({
                url: '{{ action('MqttController@file_noty') }}',
                type: 'post',
                data: data,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (result) {
                    console.log("success");
                    notification("topright", "success", "fa fa-check-circle vd_green", "success", "Notification Successfully Sent");
                    $(".btnsendnoty").attr("disable", 'false');
                },
                error: function(result){
                    console.log("error");
                    notification("topright", "success", "fa fa-check-circle vd_green", "success", "Notification Successfully Sent");
                    $(".btnsendnoty").attr("disable", 'false');
                }
            })
        });
    });</script>
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
                                <h3 class="panel-title" style="color:white"> <span class="menu-icon"> <i class="fa fa-dot-circle-o"></i> </span> Send Mass Notification </h3>
                            </div>
                            <div class="col-md-12">
                                <br/>
                                <div class="col-md-7">
                                    <form id="frmsubmit" enctype="multipart/form-data" method="post" action="{{action('MqttController@mass_noty')}}">
                                        <input type="hidden" name="multi_sel" id="multi_sel" value="on" />
                                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

                                        <div class="form-group">
                                            <label>Uplaod File</label>
                                            <input type="file" name="user_list">
                                            <label class="control-label">Select Categories</label>
                                            <div class="controls">
                                                <script type="text/javascript">
                                                    $(document).ready(function () {
                                                        $('#example-dropRight').multiselect({
                                                            buttonWidth: '300px',
                                                            dropRight: true,
                                                            onChange: function (element, checked) {
                                                                var selectedOptions = $('#example-dropRight').val();
                                                            },
                                                            enableCaseInsensitiveFiltering: true
                                                        });
                                                    });
                                                </script>

                                                <select class="form-control main_cat" name="cat_id[]" id="example-dropRight" multiple="multiple">
                                                    <?php
                                                    foreach ($categories as $val) {
                                                    ?>
                                                    <option value="<?php echo $val->id; ?>"><?php echo $val->cat_name; ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div id="android_div" class="form-group">
                                            <select class="form-control selectpicker span12" name="type" required="true" id="notytype">
                                                <option value="1">Simple Notification</option>
                                                <option value="2">Image Notification</option>
                                                <option value="3">Web Activity</option>
                                                <option value="4">News [saved in phone]</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <input class="form-control" type="text" placeholder="Title" required="true" value="" id="title" name="title"/>
                                        </div>

                                        <div class="form-group">
                                            <textarea class="form-control" id="msg" required="true" placeholder="Write message from here..." name="msg" ></textarea>
                                        </div>

                                        <div class="form-group">
                                            <div class="sec imgupload" style="display: none">
                                                <br/><label>Choose Image</label><br/>
                                                <input style="width:40%" id="add-file" placeholder="Choose File" disabled="disabled" />
                                                <div class="fileUpload btn btn-primary">
                                                    <span>Upload Photo</span>
                                                    <input  id="uploadBtn" type="file" name="image" class="upload" />
                                                </div>
                                                <br/>
                                                <img id="myImg" style="max-width: 435px;max-height:250px" src="" alt="your image" />
                                                <h3><b>OR</b></h3>
                                                <label>Direct Image Link</label><br/>
                                                <input type="text" name="image_path" value="" class="form-control" />
                                            </div>

                                        </div>
                                        <div class="mLink" style="display:none">
                                            <div class="input-prepend  span12">
                                                <label>Link (http://)</label>
                                                <input id="prependedInput"  class="span10 black" name="link" placeholder="Link to be open" type="text"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row-fluid">
                                                <button type="button" id="btn-loading" class="btn btn-primary btnsendnoty" data-loading-text="Loading...">Send Now</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <div class="simple_mokup offset1">
                                        <!--  <div class="pTitle" style="position: absolute;top: 101px;left: 91px;color: #FFF;width:250px">Title</div>
                                          <div class="pMsg" style="position: absolute;top: 123px;left: 91px;color: #FFF;width:250px">Message</div> -->
                                        <img src="{{url()}}/assets/images/simple.png"/>
                                    </div>
                                    <div class="dialoge_mokup offset1" style="display: none;">
                                        <!--<div class="pTitle" style="position: absolute;top: 258px;left: 40px;color: #000;width: 180px;border-bottom: #AAAAAA 1px solid;">Title</div>
                                        <div class="pMsg" style="position: absolute;top: 280px;left: 40px;color: #000;width: 250px;height:70px">Message</div>
                                        <div class="pEmo" style="position: absolute;top: 303px;left: 236px;color: #000;font-size: 20px"> :)</div> -->
                                        <img src="{{url()}}/assets/images/dialog_push.png"/>
                                    </div>
                                    <div class="webview_mokup offset1" style="display: none;">
                                        <img src="{{url()}}/assets/images/webview.png"/>
                                    </div>
                                    <div class="news_mokup offset1" style="display: none;">
                                        <img src="{{url()}}/assets/images/news_push.png"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layout.footer')