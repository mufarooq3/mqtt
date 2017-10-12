
<script>
    function imageIsLoadededit(e) {
        $('#myImgedit').show();
        $('#myno_img').hide();
        $('#myImgedit').attr('src', e.target.result);
        $('#image-div').hide();
    }
    ;
    $(function () {
        $('#myImgedit').hide();
        $("#edituploadBtn").change(function () {
            $(".edit-file").val($("#edituploadBtn").val());
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = imageIsLoadededit;
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
<div class="panel widget light-widget col-md-12">
    <div class="panel-body">
        <h3 class="mgbt-xs-20">Edit categories</h3>
        <hr/>
        <form class="form-horizontal" enctype="multipart/form-data" action="{{url()}}/categories/update" method="post">
            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
            <input type="hidden" name="page" id="page" value="" />
            <input type="hidden" name="id" value="<?php echo $data->id ?>" />
            <div class="form-group">
                <label class="control-label  col-md-2">Categories Name<span class="vd_red">*</span></label>
                <div id="first-name-input-wrapper"  class="controls col-md-8">
                    <input type="text" placeholder="Categories Name" value="<?php echo $data->cat_name ?>" class="width-120 required"  name="cat_name" id="cat_name" required >
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2">Description</label>
                <div id="website-input-wrapper"  class="controls col-sm-6 col-md-8">
                    <textarea placeholder="Category Description" class="width-120"  name="cat_desc" id="cat_desc"><?php echo $data->cat_desc ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Choose Image</label>
                <div id="website-input-wrapper"  class="controls col-sm-6 col-md-8">
                    <input id="add-file" placeholder="Choose File" disabled="disabled" />
                    <div class="fileUpload btn btn-primary">
                        <span>Upload Photo</span>
                        <input  id="uploadBtn" type="file" name="image" class="upload" />
                    </div>
                    <br/>
                    <img id="myImg" style="max-width: 435px;max-height:250px" src="<?php echo $data->image ?>" alt="your image" />
              </div>
            </div>
            <input type="hidden" value="1" name="is_active"/>
            <div class="form-group" style="margin-left:160px">
                <button class="btn vd_bg-green vd_white" type="submit" id="submit-register">Submit</button>
            </div>
        </form>
    </div>
</div>