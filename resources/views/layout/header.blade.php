
<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html><!--<![endif]-->

    <!-- Specific Page Data -->

    <!-- End of Data -->

    <head>
        <meta charset="utf-8" />
        <title>Advance Firebase Push Notification Admin Panel</title>
        <meta name="keywords" content="FCM,Push Notification,Admin Panel,firebase notification,fcm,firebase " />
        <meta name="description" content="Advance Firebase Push Notification Admin Panel">
        <meta name="author" content="iCanStudioZ">

        <!-- Set the viewport width to device width for mobile -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">    


        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="img/ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="img/ico/favicon.png">


        <!-- CSS -->

        <!-- Bootstrap & FontAwesome & Entypo CSS -->
        <link href="{{url()}}/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="{{url()}}/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!--[if IE 7]><link type="text/css" rel="stylesheet" href="css/font-awesome-ie7.min.css"><![endif]-->
        <link href="{{url()}}/css/font-entypo.css" rel="stylesheet" type="text/css">    

        <!-- Fonts CSS -->
        <link href="{{url()}}/css/fonts.css"  rel="stylesheet" type="text/css">

        <!-- Plugin CSS -->
        <link href="{{url()}}/plugins/jquery-ui/jquery-ui.custom.min.css" rel="stylesheet" type="text/css">    
        <link href="{{url()}}/plugins/prettyPhoto-plugin/css/prettyPhoto.css" rel="stylesheet" type="text/css">
        <link href="{{url()}}/plugins/isotope/css/isotope.css" rel="stylesheet" type="text/css">
        <link href="{{url()}}/plugins/pnotify/css/jquery.pnotify.css" media="screen" rel="stylesheet" type="text/css">    
        <link href="{{url()}}/plugins/google-code-prettify/prettify.css" rel="stylesheet" type="text/css"> 


        <link href="{{url()}}/plugins/mCustomScrollbar/jquery.mCustomScrollbar.css" rel="stylesheet" type="text/css">
        <link href="{{url()}}/plugins/tagsInput/jquery.tagsinput.css" rel="stylesheet" type="text/css">
        <link href="{{url()}}/plugins/bootstrap-switch/bootstrap-switch.css" rel="stylesheet" type="text/css">    
        <link href="{{url()}}/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css">    
        <link href="{{url()}}/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css">
        <link href="{{url()}}/plugins/colorpicker/css/colorpicker.css" rel="stylesheet" type="text/css">            

        <!-- Specific CSS -->
<!--        <link href="{{url()}}/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css">
        <link href="{{url()}}/plugins/fullcalendar/fullcalendar.print.css" rel="stylesheet" type="text/css">
        <link href="{{url()}}/plugins/introjs/css/introjs.min.css" rel="stylesheet" type="text/css">    -->

        <!-- Theme CSS -->
        <link href="{{url()}}/css/theme.min.css" rel="stylesheet" type="text/css">
        <!--[if IE]> <link href="css/ie.css" rel="stylesheet" > <![endif]-->
        <link href="{{url()}}/css/chrome.css" rel="stylesheet" type="text/chrome"> <!-- chrome only css -->    



        <!-- Responsive CSS -->
        <link href="{{url()}}/css/theme-responsive.min.css" rel="stylesheet" type="text/css"> 




        <!-- for specific page in style css -->

        <!-- for specific page responsive in style css -->


        <!-- Custom CSS -->
        <link href="{{url()}}/custom/custom.css" rel="stylesheet" type="text/css">

        {{--<link href="{{url()}}/plugins/dataTables/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">--}}
        {{--<link href="{{url()}}/plugins/dataTables/css/dataTables.bootstrap.css" rel="stylesheet" type="text/css"> --}}



        <!-- Head SCRIPTS -->
        <script type="text/javascript" src="{{url()}}/js/modernizr.js"></script> 
        <script type="text/javascript" src="{{url()}}/js/mobile-detect.min.js"></script> 
        <script type="text/javascript" src="{{url()}}/js/mobile-detect-modernizr.js"></script> 

        <script type="text/javascript" src="{{url()}}/js/jquery.js"></script> 
        <script type="text/javascript" src="{{url()}}/js/bootstrap.min.js"></script> 
        <script type="text/javascript" src='{{url()}}/plugins/jquery-ui/jquery-ui.custom.min.js'></script>

        {{--<script type="text/javascript" src="{{url()}}/plugins/dataTables/jquery.dataTables.min.js"></script>--}}
        {{--<script type="text/javascript" src="{{url()}}/plugins/dataTables/dataTables.bootstrap.js"></script>--}}

        <link href="{{url()}}/plugins/dataTables/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
        <link href="{{url()}}/plugins/dataTables/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
        <link href="{{url()}}/plugins/dataTables/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
        <link href="{{url()}}/plugins/dataTables/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
        <link href="{{url()}}/plugins/dataTables/datatables.net-scroller-bs/css/scroller.bootstrap.css" rel="stylesheet">


        <!--[if lt IE 9]>
          <script type="text/javascript" src="js/excanvas.js"></script>      
        <![endif]-->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script type="text/javascript" src="js/html5shiv.js"></script>
          <script type="text/javascript" src="js/respond.min.js"></script>     
        <![endif]-->
        <style>
            .vd_mega-menu-content::before{
                border-color:transparent transparent #002d39;
            }

            .dt-buttons{
                display: block !important;
            }
        </style>
    </head>    

    <body id="dashboard" class="full-layout  nav-right-hide nav-right-start-hide  nav-top-fixed      responsive    clearfix" data-active="dashboard "  data-smooth-scrolling="1">     
        <div class="vd_body">
            <!-- Header Start -->
            <header class="header-1" id="header" >
                <div class="vd_top-menu-wrapper" style="background-color:#002d39">
                    <div class="container">
                        <div class="vd_top-nav vd_nav-width  ">
                            <div class="vd_panel-header">
                                <div class="logo">
                                        <a href="#" style="width:400px;color:white"><h3><b>Push Notification and Promotion</b></h3></a>
                                 </div>

                                <!--“25-03-2558 19:03:24”-->
<!--                                <div class="vd_panel-menu  hidden-sm hidden-xs" data-intro="<strong>Minimize Left Navigation</strong><br/>Toggle navigation size to medium or small size. You can set both button or one button only. See full option at documentation." data-step=1>
                                    <span class="nav-medium-button menu" data-toggle="tooltip" data-placement="bottom" data-original-title="Medium Nav Toggle" data-action="nav-left-medium">
                                        <i class="fa fa-bars"></i>
                                    </span>

                                    <span class="nav-small-button menu" data-toggle="tooltip" data-placement="bottom" data-original-title="Small Nav Toggle" data-action="nav-left-small">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span> 

                                </div>-->
                                <div class="vd_panel-menu left-pos visible-sm visible-xs">

                                    <span class="menu" data-action="toggle-navbar-left">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>  


                                </div>
                                <!--                                <div class="vd_panel-menu visible-sm visible-xs">
                                                                    <span class="menu visible-xs" data-action="submenu">
                                                                        <i class="fa fa-bars"></i>
                                                                    </span>        
                                
                                                                    <span class="menu visible-sm visible-xs" data-action="toggle-navbar-right">
                                                                        <i class="fa fa-comments"></i>
                                                                    </span>                   
                                
                                                                </div>                                     -->
                                <!-- vd_panel-menu -->
                            </div>
                            <!-- vd_panel-header -->

                        </div>   

                        <div class="vd_container">
                            <div style="float: right">
						
                                 <div class="vd_mega-menu-wrapper">
                                    <div class="vd_mega-menu pull-right">
                                        <ul class="mega-ul">
								
                                            <li class="profile mega-li" id="top-menu-profile"> 
                                                    <a href="{{url()}}/logout"> <i class=" fa fa-sign-out"></i> Sign Out  </a>
                                            </li>               
                                        </ul>
                                                                
                                    </div>
                                </div>  
                            </div>

                        </div>
                    </div>
                </div>
                <!-- container --> 
        </div>
        <!-- vd_primary-menu-wrapper --> 

    </header>
    <!-- Header Ends -->