/* 
 * @auther : Nilesh Beladiya
 * @auther : worked @ icanstudioz.com
 */



$(function() {
        
    $(".notybutton").on("click",function(){
        $("#gcm_id").val($(this).attr("id"));
        $("#modal-noty").modal("show");
            
    });
        
    $(".mActive").change(function(){
        if($(this).is(":checked")){
            changeUserAccess("ACTIVE",$(this).attr("id"),$(this));
                
        }else{
            changeUserAccess("DEACTIVE",$(this).attr("id"),$(this));
        }
    });
    
    
   
    $("#modalSetting").on("click",function(){
        $("#modalSettingModal").modal("show");
    });
        
      
        
    $("#addcat").on("click",function(){
             
        $.ajax({
            url: "./helper.php",
            type: 'POST',
            data: "action=ADD_CAT&"+$("#catform").serialize(),
            success: function(data, textStatus, xhr) {
                $.gritter.add({
                    title: "Status",
                    text: data
                });
                    $("#catform")[0].reset()
            },
            error: function(xhr, textStatus, errorThrown) {
                $.gritter.add({
                    title: "Error",
                    text: "Please try later!!!"
                });
                return false;
            }
        });
        return false;
    });
        
         $("#saveCat").on("click",function(){
             
        $.ajax({
            url: "./helper.php",
            type: 'POST',
            data: "action=UPDATE_CAT&"+$("#catsaveform").serialize(),
            success: function(data, textStatus, xhr) {
                $.gritter.add({
                    title: "Status",
                    text: data
                });
                    $("#catsaveform")[0].reset();
                    
                    $("#modal-cat").modal("hide");
            },
            error: function(xhr, textStatus, errorThrown) {
                $.gritter.add({
                    title: "Error",
                    text: "Please try later!!!"
                });
                return false;
                $("#modal-cat").modal("hide");
            }
        });
        return false;
    });
        
    function changeUserAccess(type,id,act){
        $.ajax({
            url: "./helper.php",
            type: 'POST',
            data: "action="+type+"&id="+id,
            success: function(data, textStatus, xhr) {
                var str,typeN;
                if(type=="ACTIVE"){
                    str="Activated";
                    typeN="success";
                }else{
                    str="Deactivated";
                    typeN="warning";
                }
                if(!act.is(":checked") && type=="ACTIVE"){
                }
                       
                else{
                        
                        
                    $.gritter.add({
                        title: "Status",
                        text: "User "+str
                    });
                    
                    
                        
                }
                    
            },
            error: function(xhr, textStatus, errorThrown) {
                $.gritter.add({
                    title: "Error",
                    text: "Please try later!!!"
                });
                return false;
            }
        });
    }
    $('#notytype').change(function() {
        if($(this).val()==2 || $(this).val()==3 || $(this).val()==5){
            $(".mLink").fadeIn();
        }else{
            $(".mLink").fadeOut();
        }
          
        if($(this).val()==2){
            $(".mMessage").fadeIn();
            $(".mEmotion").fadeIn();
            $(".mEmo").attr("placeholder", "Emotion Eg. :)");
            $(".dialoge_mokup").fadeIn();
            $(".simple_mokup").fadeOut();
            $(".webview_mokup").fadeOut();
            $(".toast_mokup").fadeOut();
            $(".news_mokup").fadeOut();
        }else if($(this).val()==1){
            $(".mMessage").fadeIn();
            $(".mEmotion").fadeOut();
            $(".dialoge_mokup").fadeOut();
            $(".simple_mokup").fadeIn();
            $(".webview_mokup").fadeOut();
            $(".toast_mokup").fadeOut();
            $(".news_mokup").fadeOut();
        }else if($(this).val()==3){
            $(".mMessage").fadeIn();
            $(".mEmotion").fadeOut();
            $(".webview_mokup").fadeIn();
            $(".simple_mokup").fadeOut();
            $(".dialoge_mokup").fadeOut();
            $(".toast_mokup").fadeOut();
            $(".news_mokup").fadeOut();
        }else if($(this).val()==4){
            $(".mMessage").fadeOut();
            $(".mEmotion").fadeOut();
            $(".toast_mokup").fadeIn();
            $(".webview_mokup").fadeOut();
            $(".simple_mokup").fadeOut();
            $(".dialoge_mokup").fadeOut();
            $(".news_mokup").fadeOut();
        }else if($(this).val()==5){
            $(".mMessage").fadeIn();
            $(".mEmotion").fadeIn();
            $(".mEmo").attr("placeholder", "Image link to be shown in news list");
            $(".news_mokup").fadeIn();
            $(".dialoge_mokup").fadeOut();
            $(".simple_mokup").fadeOut();
            $(".webview_mokup").fadeOut();
            $(".toast_mokup").fadeOut();
        }
            
        if($(".mPreview").is(":checked")){
            $(".mPreviewContainer").fadeIn();
        }else{
            $(".mPreviewContainer").fadeOut();
        }
    });
        
    $(".mPreview").change(function(){
        if($(".mPreview").is(":checked")){
            $(".mPreviewContainer").fadeIn();
        }else{
            $(".mPreviewContainer").fadeOut();
        }
    });
        
    $(".changePassword").on("click",function(){
            
        $.ajax({
            url: "./helper.php",
            type: 'POST',
            data: $("#settingForm").serialize()+"&action=CHANGE_PASS",
            success: function(data, textStatus, xhr) {
                   
                $("#modalSettingModal").modal("hide");
                $.gritter.add({
                    title: "Notification",
                    text: data
                });
                 
                    
            },
            error: function(xhr, textStatus, errorThrown) {
                $.gritter.add({
                    title: "Error",
                    text: "Please try later!!!"
                });
                return false;
            }
        });
    });
        
    $(".sendnoty").on("click",function(){
         
        $.ajax({
            url: "./send_gcm.php",
            type: 'POST',
            data: $("#notyform").serialize(),
            success: function(data, textStatus, xhr) {
                   
                $.gritter.add({
                    title: "Status",
                    text: data
                });
                $("#modal-noty").modal("hide");
                    
            },
            error: function(xhr, textStatus, errorThrown) {
                $.gritter.add({
                    title: "Error",
                    text: "Please try later!!!"
                });
                $("#modal-noty").modal("hide");
                return false;
            }
        });
            
    });

    function parseDate(d) {
        var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ],
        d2 = monthNames[d.getMonth()] +' '+ d.getDate() +', '+d.getFullYear() +' '+d.getHours() +':'+d.getMinutes();
        return d2;
    }
        
    function JSDate(d){
        var t = d.split(/[- :]/);
        // Apply each element to the Date function
        var nd=new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
        return nd;
    }
    $("#showGraph").on("click",function(){
        var unique_id=$.gritter.add({
            title: 'Please wait...!',
            text: ' Loading charts...',
            image: 'assets/images/preloader.gif',
  
            class_name: 'gritter-default'
        });
        $.ajax({
            url: "./graphs.php",
            type: 'POST',
            dataType: "json",
            data: $("#graphform").serialize(),
            success: function(dataJSON) {
                var dataJ=dataJSON;
                var ticksize = Object.keys(dataJ).length;
                // console.log(dataJ);
                $('.dynamicTableGCM').dataTable().fnClearTable();
                var html="";
                var dateJ;
                var success=0;
                var failed=0;
                var updated=0;
                var removed=0;
                $.each(dataJ,function(){
                    dateJ=JSDate(this.time);
                    html+="<tr class='gradeX'><td>"+this.type+"</td><td>"+this.title+"</td><td>"+this.message+"</td><td>"+this.link+"</td><td>"+this.emotion+"</td><td>"+ parseDate(dateJ)+"</td><td>"+ this.passed+"</td><td>"+ this.failed+"</td><td>"+ this.updated+"</td><td>"+ this.removed+"</td></tr>";
                     
                    success=(success+parseInt(this.passed));
                    failed=(failed+parseInt(this.failed));
                    updated=(updated+parseInt(this.updated));
                    removed=(removed+parseInt(this.removed));
                });
                    
                $(".TableGCMBody").html(html).fadeIn();
                if ($('.dynamicTableGCM').size() > 0)
                {
                    $('.dynamicTableGCM').dataTable({
                        "sPaginationType": "bootstrap",
                        "bDestroy" : true,
                        "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                        "oLanguage": {
                            "sLengthMenu": "_MENU_ records per page"
                        }
                    });
                }
                var total=(parseInt(success)+parseInt(failed)+parseInt(updated)+parseInt(removed));
                //  alert(total);
                success=(success*100/total);
                failed=(failed*100/total);
                updated=(updated*100/total);
                removed=(removed*100/total);
                                
                var ti=[];
                var charts={
                    chart_lines_fill_nopoints: 
                    {
                        // chart data
                        data: 
                        {
                            d1: [],
                            d2: [],
                            d3:[],
                            d4:[]
                        },

                        // will hold the chart object
                        plot: null,

                        // chart options
                        options: 
                        {
                            bars: {
                                show:true,
                                barWidth:0.2,
                                fill:1
                            },
                            grid: {
                                show: true,
                                aboveData: false,
                                color: "#3f3f3f" ,
                                labelMargin: 5,
                                axisMargin: 0, 
                                borderWidth: 0,
                                borderColor:null,
                                minBorderMargin: 5 ,
                                clickable: true, 
                                hoverable: true,
                                autoHighlight: false,
                                mouseActiveRadius: 20,
                                backgroundColor : { }
                            },
                            series: {
                                grow: {
                                    active:false
                                }
                            },
                            legend: {
                                position: "ne"
                            },
                            xaxis:{
                                    
                                ticks:ti, 
                                    
                            },
                            colors: [],
                            tooltip: false,
                            tooltipOpts: {
                                content: "%s : %y.0",
                                shifts: {
                                    x: -30,
                                    y: -50
                                },
                                defaultTheme: false
                            }
                        },

                        // initialize
                        init: function()
                        {
                            // apply styling
                            charts.utility.applyStyle(this);
                            var d11=[];
                            var d22=[];
                            var d12=[];
                            var d21=[];
                                
                            var d33=[];
                            // generate some data
                            var i=0;
                            $.each(dataJ,function(){
                                //   alert(this.nid);
                                //console.log(this);
                                d11.push(i++);
                                d11.push(this.passed);
                                    
                                d22.push(i++);
                                d22.push(this.failed);
                                 
                                d12.push(i++);
                                d12.push(this.updated);
                                 
                                d21.push(i++);
                                d21.push(this.removed);
                                 
                                d33.push(i++);
                                d33.push(JSDate(this.time).getDay()+"/"+(JSDate(this.time).getMonth()+1));
                                 
                                ti.push(d33);
                                // ticks.push(this.time);
                                charts.chart_lines_fill_nopoints.data.d1.push(d11);    
                                charts.chart_lines_fill_nopoints.data.d3.push(d12);    
                                charts.chart_lines_fill_nopoints.data.d4.push(d21);    
                                charts.chart_lines_fill_nopoints.data.d2.push(d22);
                                d11=[];
                                d22=[];
                                d33=[];
                                d12=[];
                                d21=[];
                                    
                            });
                            
                    
                            // console.log(ticks);
                            // make chart
                            this.plot = $.plot(
                                '#chart_lines_fill_nopoints', 
                                [{
                                    label: "Success", 
                                    data: this.data.d1,
                                    bars: {
                                        order: 1
                                    },
                                    lines: {
                                        fillColor: "#fff8f2"
                                    },
                                    points: {
                                        fillColor: "#88bbc8"
                                    }
                                }, 
                                {	
                                    label: "Failed", 
                                    data: this.data.d2,
                                    bars: {
                                        order: 2
                                    },
                                    lines: {
                                        fillColor: "rgba(0,0,0,0.1)"
                                    },
                                    points: {
                                        fillColor: "#ed7a53"
                                    }
                                },{	
                                    label: "Updated", 
                                    data: this.data.d3,
                                    bars: {
                                        order: 2
                                    },
                                    lines: {
                                        fillColor: "rgba(0,0,0,0.1)"
                                    },
                                    points: {
                                        fillColor: "#cccccc"
                                    }
                                },{	
                                    label: "Removed", 
                                    data: this.data.d4,
                                    bars: {
                                        order: 2
                                    },
                                    lines: {
                                        fillColor: "rgba(0,0,0,0.1)"
                                    },
                                    points: {
                                        fillColor: "#aaaaaa"
                                    }
                                }], 
                                this.options);
                        }
                    },
                    chart_donut:

                    {
                        // chart data
                        data: [
                        {
                            label: "Success",  
                            data: success
                        },
{
                            label: "Failed",  
                            data: failed
                        },
{
                            label: "updated",  
                            data: updated
                        },
{
                            label: "Removed",  
                            data: removed
                        }
                                
                        ],

                        // will hold the chart object
                        plot: null,

                        // chart options
                        options: 
                        {
                            series: {
                                pie: { 
                                    show: true,
                                    innerRadius: 0.4,
                                    highlight: {
                                        opacity: 0.1
                                    },
                                    radius: 1,
                                    stroke: {
                                        color: '#fff',
                                        width: 8
                                    },
                                    startAngle: 2,
                                    combine: {
                                        color: '#EEE',
                                        threshold: 0.00
                                    },
                                    label: {
                                        show: true,
                                        radius: 1,
                                        formatter: function(label, series){
                                            return '<div class="label label-inverse">'+label+'&nbsp;'+Math.round(series.percent)+'%</div>';
                                        }
                                    }
                                },
                                grow: {
                                    active: false
                                }
                            },
                            legend:{
                                show:true
                            },
                            grid: {
                                hoverable: true,
                                clickable: true,
                                backgroundColor : { }
                            },
                            colors: [],
                            tooltip: true,
                            tooltipOpts: {
                                content: "%s : %y.1"+"%",
                                shifts: {
                                    x: -30,
                                    y: -50
                                },
                                defaultTheme: false
                            }
                        },
		
                        // initialize
                        init: function()
                        {
                            // apply styling
                            charts.utility.applyStyle(this);
			
                            this.plot = $.plot($("#chart_donut"), this.data, this.options);
                        }
                    },
                    initIndex: function()

                    {
                        // chart_ordered_bars
                        this.chart_ordered_bars.init();
                            
                    // init traffic sources pie
                   
                    },

                    // utility class
                    utility:
                    {
                        chartColors: [ themerPrimaryColor, "#444", "#777", "#999", "#DDD", "#EEE" ],
                        chartBackgroundColors: ["#fff", "#fff"],

                        applyStyle: function(that)
                        {
                            that.options.colors = charts.utility.chartColors;
                            that.options.grid.backgroundColor = {
                                colors: charts.utility.chartBackgroundColors
                            };
                            that.options.grid.borderColor = charts.utility.chartColors[0];
                            that.options.grid.color = charts.utility.chartColors[0];
                        },
                        randNum: function()
                        {
                            return (Math.floor( Math.random()* (1+40-20) ) ) + 20;
                        }
                    }
                }
                    
                    
                    
                var previousPoint = null;
                $("#chart_lines_fill_nopoints").bind("plothover", function (event, pos, item) {
                    $("#x").text(pos.x.toFixed(2));
                    $("#y").text(pos.y.toFixed(2));

                    if (item) {
                        if (previousPoint != item.datapoint) {
                            previousPoint = item.datapoint;
                            $("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2), y = item.datapoint[1];
                            showTooltip(item.pageX, item.pageY,item.series.label + " : " + y);
                        }
                    }
                    else {
                        $("#tooltip").remove();
                        previousPoint = null;            
                    }

                });

                // show the tooltip
                function showTooltip(x, y, contents) {
                    $('<div id="tooltip">' + contents + '</div>').css( {
                        position: 'absolute',
                        display: 'none',
                        top: y - 35,
                        left: x + 5,
                        border: '1px solid #fdd',
                        padding: '2px',
                        'background-color': '#fee',
                        opacity: 0.90
                    }).appendTo("body").fadeIn(200);
                }

                charts.chart_donut.init();
                charts.chart_lines_fill_nopoints.init();
                $.gritter.remove(unique_id, { 
                    fade: true, // optional
                    speed: 'fast' // optional
                });
            },
            error: function() {
                $.gritter.add({
                    title: "Error",
                    text: "Please try later!!!"
                });
                return false;
            }
        });
        return false;
    });
        
        
    $(".mTitle").on("blur",function(){
        $(".pTitle").html($(this).val());
    });
        
    $(".mMsg").on("blur",function(){
        $(".pMsg").html($(this).val());
    });
    $(".mEmo").on("blur",function(){
        $(".pEmo").html($(this).val());
    });
        
//charts JS
});    

