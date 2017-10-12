<div class="panel widget light-widget col-md-12">
    <div class="panel-body">
        <h3 class="mgbt-xs-20">Edit Group</h3>
        <hr/>
        <form class="form-horizontal"  action="{{url()}}/groups/update" method="post" role="form" id="register-form">
            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
            <input type="hidden" name="page" id="page" value="" />
            <input type="hidden" name="id" value="<?php echo $data->id ?>" />
            <div class="form-group">
                <label class="control-label  col-md-3">Group Name<span class="vd_red">*</span></label>
                <div id="first-name-input-wrapper"  class="controls col-md-7">
                    <input type="text" placeholder="Group Name" value="<?php echo $data->name ?>" class="width-120 required"  name="name" id="grp_name" required >
                </div>
            </div>

            <input type="hidden" value="1" name="is_active"/>
            <div class="form-group" style="margin-left:160px">
                <button class="btn vd_bg-green vd_white" type="submit" id="submit-register">Submit</button>
            </div>
        </form>
    </div>
</div>