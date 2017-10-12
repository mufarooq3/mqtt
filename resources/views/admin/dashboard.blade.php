@include('layout.header')
@include('layout.sidebar')
<!-- Flot Chart  -->

<script type="text/javascript" src="{{url()}}/plugins/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="{{url()}}/plugins/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="{{url()}}/plugins/flot/jquery.flot.pie.min.js"></script>
<script type="text/javascript" src="{{url()}}/plugins/flot/jquery.flot.categories.min.js"></script>
<script type="text/javascript" src="{{url()}}/plugins/flot/jquery.flot.time.min.js"></script>
<script type="text/javascript" src="{{url()}}/plugins/flot/jquery.flot.animator.min.js"></script>
<script>

$(window).load(function() 
	{
	"use strict";
		var pie_placeholder = $("#pie-chart");

		var pie_data = [];
		var other = 0; 
	<?php 
		$i = 0;
		if(!empty($data['cnt_device_os'])){
		foreach($data['cnt_device_os'] as $v){
			if($v->label == 'M' || $v->label == 'L' || $v->label == 'K'){
			?>
			
				pie_data[<?php echo $i ?>] = {
					label: "<?php echo $v->label ?>",
					data: <?php echo $v->data ?>
				};
			
		
		<?php $i++;
			}else{ ?>
			
				other = other + <?php echo $v->data ?>
			
		<?php	}
		 
		}
		}
		?>
		pie_data[<?php echo $i ?>] = {
			label: "OTHER",
			data: other
		};
		var plot_1 = $.plot(pie_placeholder, pie_data, {
			series: {
				pie: { 
					show: true,
					label:{
						show: true,
						radius: .5,
						innerRadius: 0,
						formatter: labelFormatter,
						background: {
							opacity: 0
						}
					},

				}
			},
			grid: {
				hoverable: true,
				clickable: true
			},
			colors: ["#FCB660", "#ce91db", "#56A2CF", "#52D793", "#FC8660", "#CCCCCC"]
		});
		pie_placeholder.bind("plothover", function(event, pos, obj) {
			if (!obj) {
				return;
			}
			var percent = parseFloat(obj.series.percent).toFixed(2);
			$("#hover").html("<span style='font-weight:bold; color:" + obj.series.color + "'>" + obj.series.label + " (" + percent + "%)</span>");
		});

		pie_placeholder.bind("plotclick", function(event, pos, obj) {
			if (!obj) {
				return;
			}
			percent = parseFloat(obj.series.percent).toFixed(2);
			alert(""  + obj.series.label + ": " + percent + "%");
		});
		function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
	}
	
		/* DONUT CHART */
		var data_donut = [],
			series = 10;
			var str = '';
			var other = 0; 
			var data_donut = <?php echo !empty($data['cnt_device_model'])? $data['cnt_device_model'] : ''?>;
		
	/*	var data_donut = [
			{ label: "35% New Visitor",  data: 35},
			{ label: "65% Returning Visitor",  data: 65}
		]; */
		var revenue_donut_chart = $("#donut-chart");
		
		$("#donut-chart").bind("plotclick", function (event, pos, item) {
			if (item) {
				$("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
				plot.highlight(item.series, item.datapoint);
			}
		});
		var plot_2 = $.plot(revenue_donut_chart, data_donut, {
			series: {
				pie: { 
					radius: 0.7,
					innerRadius: 0.4,
					show: true
				}
			},
			grid: {
				hoverable: true,
				clickable: true,
			},
			 colors: ["#1FAE66", "#F85D2C", "#FABDBC", "#23709E", "#777777"]				
		});
		
		
		
		
		
		var pie_placeholder_ios = $("#pie-chart-ios");

		var pie_data_ios = [];
		var other_ios = 0; 
	<?php 
		$i = 0;
		if(!empty($data['cnt_device_os_ios'])){
		foreach($data['cnt_device_os_ios'] as $v){
			$lab = explode('.',$v->label);
			
			if($lab[0] == 'iOS 10' || $lab[0] == 'iOS 9' || $lab[0] == 'iOS 8'){
			?>
				
				pie_data_ios[<?php echo $i ?>] = {
					label: "<?php echo $v->label ?>",
					data: <?php echo $v->data ?>
				};
			
		
		<?php $i++;
			}else{ ?>
			
				other_ios = other_ios + <?php echo $v->data ?>
			
		<?php	}
		 
		}
		}
		?>
		pie_data_ios[<?php echo $i ?>] = {
			label: "OTHER",
			data: other
		};
		var plot_1 = $.plot(pie_placeholder_ios, pie_data_ios, {
			series: {
				pie: { 
					show: true,
					label:{
						show: true,
						radius: .5,
						innerRadius: 0,
						formatter: labelFormatter_ios,
						background: {
							opacity: 0
						}
					},

				}
			},
			grid: {
				hoverable: true,
				clickable: true
			},
			colors: ["#FCB660", "#ce91db", "#56A2CF", "#52D793", "#FC8660", "#CCCCCC"]
		});
		pie_placeholder_ios.bind("plothover", function(event, pos, obj) {
			if (!obj) {
				return;
			}
			var percent = parseFloat(obj.series.percent).toFixed(2);
			$("#hover").html("<span style='font-weight:bold; color:" + obj.series.color + "'>" + obj.series.label + " (" + percent + "%)</span>");
		});

		pie_placeholder_ios.bind("plotclick", function(event, pos, obj) {
			if (!obj) {
				return;
			}
			percent = parseFloat(obj.series.percent).toFixed(2);
			alert(""  + obj.series.label + ": " + percent + "%");
		});
		function labelFormatter_ios(label, series) {
			return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
		}
		
		
		
		
		// for IOS
		var data_donut = [],
			series = 10;
			var str = '';
			var other = 0; 
			var data_donut = <?php echo !empty($data['cnt_device_model_ios'])? $data['cnt_device_model_ios'] : ''?>;
		
	/*	var data_donut = [
			{ label: "35% New Visitor",  data: 35},
			{ label: "65% Returning Visitor",  data: 65}
		]; */
		var revenue_donut_chart = $("#donut-chart-ios");
		
		$("#donut-chart").bind("plotclick", function (event, pos, item) {
			if (item) {
				$("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
				plot.highlight(item.series, item.datapoint);
			}
		});
		var plot_2 = $.plot(revenue_donut_chart, data_donut, {
			series: {
				pie: { 
					radius: 0.7,
					innerRadius: 0.4,
					show: true
				}
			},
			grid: {
				hoverable: true,
				clickable: true,
			},
			 colors: ["#1FAE66", "#F85D2C", "#FABDBC", "#23709E", "#777777"]				
		});
		
		
	});
</script>
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
				<div class="row">

                      <div class="col-md-12">

                        <div class="row">
						<div class="col-xs-4">
								<div class="vd_status-widget vd_bg-yellow widget">
										<a class="panel-body" href="{{url()}}/users">

                                        <div class="clearfix">

                                            <span class="menu-icon">

                                                <i class="fa fa-mobile"></i>

                                            </span>

                                            <span class="menu-value">

                                                <?php 
												empty($data['cnt_iphone'])?$data['cnt_iphone'] = 0:'';
												empty($data['cnt_android'])?$data['cnt_android'] = 0:'';
												echo $data['cnt_android'] + $data['cnt_iphone']; 
												
												?>

                                            </span>

                                        </div>

                                        <div class="menu-text clearfix">

                                            Total device installed

                                        </div>

                                    </a>

                                </div>

								</div>
								
								
							<div class="col-xs-4">
								<div class="vd_status-widget vd_bg-red  widget">
										<a class="panel-body" href="{{url()}}/users">

                                        <div class="clearfix">

                                            <span class="menu-icon">

                                                <i class="fa fa-android"></i>

                                            </span>

                                            <span class="menu-value">

                                                <?php
												
												empty($data['cnt_android'])?$data['cnt_android'] = 0:'';
												echo $data['cnt_android']; 
												?>

                                            </span>

                                        </div>

                                        <div class="menu-text clearfix">

                                            Total Android device installed

                                        </div>

                                    </a>

                                </div>

								</div>
									<div class="col-xs-4">
										<div class="vd_status-widget vd_bg-blue widget">
											<a class="panel-body" href="{{url()}}/users">

											<div class="clearfix">

												<span class="menu-icon">

													<i class="fa fa-apple"></i>

												</span>

												<span class="menu-value">

													<?php 
													empty($data['cnt_iphone'])?$data['cnt_iphone'] = 0:'';
													echo $data['cnt_iphone']; 
													?>

												</span>

											</div>

											<div class="menu-text clearfix">

												Total IPHONE device installed

											</div>

										</a>

									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div  style="padding:10px 10px 10px 10px" class="row">
				<?php if(!empty($data['cnt_device_os_ios'])) {?>
					<div class="col-md-6">
						<div class="panel vd_map-widget widget">
							<div class="panel-heading vd_bg-blue">
								<h3 class="panel-title"> <span class="menu-icon"> <i class="icon-pie"></i> </span>DEVICE OS IPHONE</h3>
							</div>
							<div class="panel-body">
								<div class="">
									<div class="panel-body">
										<div id="pie-chart-ios" class="pie-chart" style="height:250px;">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } 
				
				if(!empty($data['cnt_device_model_ios'])) {?>
					<div class="col-md-6">
						<div class="panel vd_map-widget widget">
							<div class="panel-heading vd_bg-blue">
								<h3 class="panel-title"> <span class="menu-icon"> <i class="icon-pie"></i> </span>DEVICE MODEL IPHONE</h3>
							</div>
							<div class="panel-body">
								<div class="">
									<div class="panel-body">
										<div id="donut-chart-ios" class="donut-chart" style="height:253px;"></div>
									</div>
								</div>
							</div>
						</div>	
					</div>
				<?php } ?>
				</div>
				
				<div  style="padding:10px 10px 10px 10px" class="row">
					<div class="col-md-6">
						<div class="panel vd_map-widget widget">
							<div class="panel-heading vd_bg-blue">
								<h3 class="panel-title"> <span class="menu-icon"> <i class="icon-pie"></i> </span>DEVICE OS ANDROID</h3>
							</div>
							<div class="panel-body">
								<div class="">
									<div class="panel-body">
										<div id="pie-chart" class="pie-chart" style="height:250px;">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="panel vd_map-widget widget">
							<div class="panel-heading vd_bg-blue">
								<h3 class="panel-title"> <span class="menu-icon"> <i class="icon-pie"></i> </span>DEVICE MODEL ANDROID</h3>
							</div>
							<div class="panel-body">
								<div class="">
									<div class="panel-body">
										<div id="donut-chart" class="donut-chart" style="height:253px;"></div>
									</div>
								</div>
							</div>
						</div>	
					</div>
				
				</div>
				
				
			
            <div class="vd_content-section clearfix">
				<div class="row">

                        <div class="col-md-12">

                        <div class="row">

                            <div class="col-xs-3">

                                <div class="vd_status-widget vd_bg-red  widget">
										<a class="panel-body" href="{{url()}}/users">

                                        <div class="clearfix">

                                            <span class="menu-icon">

                                                <i class="fa fa-users"></i>

                                            </span>

                                            <span class="menu-value">

                                                <?php echo $data['cnt_users']; ?>

                                            </span>

                                        </div>

                                        <div class="menu-text clearfix">

                                            Total Users

                                        </div>

                                    </a>

                                </div>

                            </div>

                            <!--col-xs-6 -->

                            <div class="col-xs-3">

                                <div class="vd_status-widget vd_bg-blue widget">

                                     <a class="panel-body" href="{{url()}}/SendNoty">

                                        <div class="clearfix">

                                            <span class="menu-icon">

                                                <i class="fa fa-bolt"></i>

                                            </span>

                                            <span class="menu-value">

                                                <?php echo   $data['cnt_noti']; ?>

                                            </span>

                                        </div>

                                        <div class="menu-text clearfix" style="font-size: 14px;">

                                            Total Notifications sent

                                        </div>

                                    </a>

                                </div>

                            </div>

                            <!--col-xs-6 -->
							<div class="col-xs-3">

                                <div class="vd_status-widget vd_bg-yellow widget">

                                     <a class="panel-body" href="{{url()}}/categories">

                                        <div class="clearfix">

                                            <span class="menu-icon">

                                                <i class="fa fa-users"></i>

                                            </span>

                                            <span class="menu-value">

                                                <?php echo $data['cnt_cat']; ?>

                                            </span>

                                        </div>

                                        <div class="menu-text clearfix">

                                            Total Categories

                                        </div>

                                    </a>

                                </div>

                            </div>

                            <!--col-xs-6 -->

                            <div class="col-xs-3">

                                <div class="vd_status-widget vd_bg-grey widget">





                                    <a class="panel-body" href="{{url()}}/users">

                                        <div class="clearfix">

                                            <span class="menu-icon">

                                                <i class="icon-users"></i>

                                            </span>

                                            <span class="menu-value">

                                              <?php echo $data['cnt_last_mnth_user']; ?>

                                            </span>

                                        </div>

                                        <div class="menu-text clearfix" style="font-size: 14px;">

                                            New Users in last month

                                        </div>

                                    </a>

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