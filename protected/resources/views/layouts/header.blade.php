<?php 

use App\Models\AdminSettings;
use App\Models\Notifications;
use App\Models\Messages;
if( Auth::check() ) {
    
    // Notifications    
    $notifications_count = Notifications::where('destination',Auth::user()->id)->where('status',0)->count();
    
    // Messages 
    $messages_count = Messages::where('to_user_id',Auth::user()->id)->where('status','new')->count();
    
    if( $messages_count != 0 &&  $notifications_count != 0 ) {
        $totalNotifications = '('.( $messages_count + $notifications_count ).') ';
        $totalNotify = ( $messages_count + $notifications_count );
    } else if( $messages_count == 0 &&  $notifications_count != 0  ) {
        $totalNotifications = '('.$notifications_count.') ';
        $totalNotify = $notifications_count;
    } else if ( $messages_count != 0 &&  $notifications_count == 0 ) {
        $totalNotifications = '('.$messages_count.') ';
        $totalNotify = $messages_count;
    } else {
        $totalNotifications = null;
        $totalNotify = null;
    }
 } else {
    $totalNotifications = null;
    $totalNotify = null;
 }
?>
<!doctype html>
<html lang="en">
<head>
    <?php $settings = AdminSettings::first(); ?>
    <meta name="csrf-token" content="{{ csrf_token() }}">


<!-- Basic Page Needs
================================================== -->
<title>@yield('title')</title>
@include('includes.css_general')
@if( Auth::check() )
<script type="text/javascript">
//<----- Notifications
function Notifications() {  
     
     var _title = '@section("title")@show {{e($settings->title)}}';
     
     console.time('cache');
     
     $.get(URL_BASE+"/notifications", function( data ) {   
        if ( data ) {
            //* Messages */
            if( data.messages != 0 ) {
                
                var totalMsg = data.messages;
                
                $('#noti_msg').html(data.messages).fadeIn();
            } else {
                $('#noti_msg').fadeOut().html('');
                
                if(  data.notifications == 0 ) {
                     $('title').html( _title );
                }
            }
            
            //* Notifications */
            if( data.notifications != 0 ) {
                
                var totalNoty = data.notifications;
                $('#noti_connect').html(data.notifications).fadeIn();
            } else {
                $('#noti_connect').fadeOut().html('');
            }
            
            //* Error */
            if( data.error == 1 ) {
                window.location.reload();
            }
            
            var totalGlobal = parseInt( totalMsg ) + parseInt( totalNoty );
            
            if( data.notifications == 0 && data.messages == 0 ) {
                $('.notify').hide();
            }
        
        if( data.notifications != 0 && data.messages != 0 ) {
            $('title').html( "("+ totalGlobal + ") " + _title );
          } else if( data.notifications != 0 && data.messages == 0 ) {
            $('title').html( "("+ data.notifications + ") " + _title );
          } else if( data.notifications == 0 && data.messages != 0 ) {
            $('title').html( "("+ data.messages + ") " + _title );
          } 
        
        }//<-- DATA
            
        },'json');
        
        console.timeEnd('cache'); 
}//End Function TimeLine
    
timer = setInterval("Notifications()", 10000);
</script>
@endif
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- CSS
================================================== -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/colors/blue.css') }}">
<link rel="shortcut icon" href="{{ URL::asset('public/img/favicon.ico') }}" />
@yield('css_style')
</head>
<body>
 @if( Auth::check() )
     
     @if ( !filter_var( Auth::user()->email, FILTER_VALIDATE_EMAIL ) )
     <!-- ***** Modal Mail ****** -->
    <div class="modal fade" id="myModalMail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h5 class="modal-title text-left" id="myModalLabel">{{ Lang::get('misc.email_valid') }}</h5>
          </div>
          <div class="modal-body">
            
         <form action="{{ URL::to('/') }}/ajax/updatemail" method="POST" accept-charset="UTF-8" id="updateEmail" enctype="multipart/form-data">
         @csrf
           <input class="form-control" value="{{Auth::user()->email}}" name="email" id="email" />
          </div><!-- modal-body -->
                          
                <div class="modal-footer">
                   <div class="alert alert-danger btn-sm text-left col-thumb" role="alert" id="errors" style="display:none;"></div>
                        <button type="submit" id="button_update_mail" class="btn btn-info btn-sm btn-sort pull-left">{{ Lang::get('auth.send') }}</button>
                          </div><!-- modal-footer" -->
                         </form>
                        </div>
                      </div>
    </div> <!-- ***** Modal Mail ****** -->
    @endif
        <div class="modal fade" id="listModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content"> 
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title text-center" id="myModalLabel">
                            {{ Lang::get('users.your_lists') }}
                            </h4>
                     </div><!-- Modal header -->
                     
                      <div class="modal-body listWrap">
                        <div class="form-group form-li display-none" id="listsContainer"></div><!-- form-group -->
                        
            
            <div class="btn-block display-none add-lists">
                
                <button type="button" data-toggle="modal" data-target="#addListModal" class="btn btn-sm btn-success bt-add-list">
                        <i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('users.create_list') }}
                </button>
                
                <button type="button" style="display: none;" id="done" data-dismiss="modal" class="btn btn-sm btn-danger bt-add-list">
                        {{ Lang::get('users.done') }}
                </button>
                
            </div><!-- btn-block -->
                        
                      </div><!-- Modal body -->
                    </div><!-- Modal content -->
                </div><!-- Modal dialog -->
            </div><!-- Modal -->
            
            <!-- ***** Modal Create List ****** -->
    <div class="modal fade" id="addListModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-left" id="myModalLabel"><strong>{{ Lang::get('users.create_list') }}</strong></h4>
          </div>
          
          <div class="modal-body">
            
         <form class="form-horizontal" id="form_add_list" method="post" role="form" action="">
             <input type="hidden" name="user_id" value="" id="user_id_data" />
             <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('users.name') }}</label>
                <div class="col-sm-10">
                  <input type="text" value="" name="name" class="form-control input-sm" id="title" placeholder="{{ Lang::get('users.name') }}">
            
                </div>
              </div><!-- **** form-group **** -->
            
             <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.type') }}</label>
                <div class="col-sm-10">
                    
                    <select id="project" name="type" class="input-sm btn-block">
                      <option value="1">{{ Lang::get('misc.public') }}</option>
                        <option value="0">{{ Lang::get('misc.private') }}</option>
                    </select>

                </div>
              </div><!-- **** form-group **** -->
                
              <div class="form-group">
                <label class="col-sm-2 control-label input-sm">{{ Lang::get('misc.description') }} ({{ Lang::get('misc.optional') }})</label>
                <div class="col-sm-10">
                  <textarea name="description" rows="4" id="_description" class="form-control input-sm textarea-textx"></textarea>
                
                <div class="alert alert-danger btn-sm text-left col-thumb" role="alert" id="error-show-msg" style="display:none; margin-top: 10px;"></div>
                
                </div>
                
              </div><!-- **** form-group **** -->
                          
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button style="padding: 9px 30px;" type="submit" class="btn btn-info btn-sm btn-sort" id="send_list">
                    {{ Lang::get('users.create') }}
                    </button>
                </div>
              </div><!-- **** form-group **** -->
              
            </form><!-- **** form **** -->
                
          </div><!-- modal-body -->
        </div>
      </div>
    </div> <!-- ***** Modal ****** -->
     @endif

<!-- Wrapper -->
<div id="wrapper">

<?php 
$userAuth = Auth::user(); 
if( Auth::check() ) {
    
    // Notifications    
    $notifications_count = Notifications::where('destination',Auth::user()->id)->where('status',0)->count();
    
    // Messages 
    $messages_count = Messages::where('to_user_id',Auth::user()->id)->where('status','new')->count();
}
?>

<!-- Header Container
================================================== -->
<header id="header-container" class="fullwidth">

    <!-- Header -->
    <div id="header">
        <div class="container">
            
            <!-- Left Side Content -->
            <div class="left-side">
                
                <!-- Logo -->
                <div id="logo">
                    <a href="{{url('/')}}"><img src="{{ asset('images/logo.png') }}" alt=""></a>
                </div>

                <!-- Main Navigation -->
                <nav id="navigation">
                    <ul id="responsive">

                        <li><a href="{{url('/')}}" class="current">Home</a>
                           <!--  <ul class="dropdown-nav">
                                <li><a href="index.html">Home 1</a></li>
                                <li><a href="index-2.html">Home 2</a></li>
                                <li><a href="index-3.html">Home 3</a></li>
                            </ul> -->
                        </li>

                        <li><a href="#">Find Work</a>
                            <ul class="dropdown-nav">
                                <li><a href="#">Browse Jobs</a>
                                    <ul class="dropdown-nav">
                                        <li><a href="jobs-list-layout-full-page-map.html">Full Page List + Map</a></li>
                                        <li><a href="jobs-grid-layout-full-page-map.html">Full Page Grid + Map</a></li>
                                        <li><a href="jobs-grid-layout-full-page.html">Full Page Grid</a></li>
                                        <li><a href="jobs-list-layout-1.html">List Layout 1</a></li>
                                        <li><a href="jobs-list-layout-2.html">List Layout 2</a></li>
                                        <li><a href="jobs-grid-layout.html">Grid Layout</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Browse Tasks</a>
                                    <ul class="dropdown-nav">
                                        <li><a href="tasks-list-layout-1.html">List Layout 1</a></li>
                                        <li><a href="tasks-list-layout-2.html">List Layout 2</a></li>
                                        <li><a href="tasks-grid-layout.html">Grid Layout</a></li>
                                        <li><a href="tasks-grid-layout-full-page.html">Full Page Grid</a></li>
                                    </ul>
                                </li>
                                <li><a href="browse-companies.html">Browse Companies</a></li>
                                <li><a href="single-job-page.html">Job Page</a></li>
                                <li><a href="single-task-page.html">Task Page</a></li>
                                <li><a href="single-company-profile.html">Company Profile</a></li>
                            </ul>
                        </li>

                        <li><a href="#">For Employers</a>
                            <ul class="dropdown-nav">
                                <li><a href="#">Find a Freelancer</a>
                                    <ul class="dropdown-nav">
                                        <li><a href="freelancers-grid-layout-full-page.html">Full Page Grid</a></li>
                                        <li><a href="freelancers-grid-layout.html">Grid Layout</a></li>
                                        <li><a href="freelancers-list-layout-1.html">List Layout 1</a></li>
                                        <li><a href="freelancers-list-layout-2.html">List Layout 2</a></li>
                                    </ul>
                                </li>
                                <li><a href="single-freelancer-profile.html">Freelancer Profile</a></li>
                                <li><a href="dashboard-post-a-job.html">Post a Job</a></li>
                                <li><a href="dashboard-post-a-task.html">Post a Task</a></li>
                            </ul>
                        </li>

                        <li><a href="#">Dashboard</a>
                            <ul class="dropdown-nav">
                                <li><a href="dashboard.html">Dashboard</a></li>
                                <li><a href="dashboard-messages.html">Messages</a></li>
                                <li><a href="dashboard-bookmarks.html">Bookmarks</a></li>
                                <li><a href="dashboard-reviews.html">Reviews</a></li>
                                <li><a href="dashboard-manage-jobs.html">Jobs</a>
                                    <ul class="dropdown-nav">
                                        <li><a href="dashboard-manage-jobs.html">Manage Jobs</a></li>
                                        <li><a href="dashboard-manage-candidates.html">Manage Candidates</a></li>
                                        <li><a href="dashboard-post-a-job.html">Post a Job</a></li>
                                    </ul>
                                </li>
                                <li><a href="dashboard-manage-tasks.html">Tasks</a>
                                    <ul class="dropdown-nav">
                                        <li><a href="dashboard-manage-tasks.html">Manage Tasks</a></li>
                                        <li><a href="dashboard-manage-bidders.html">Manage Bidders</a></li>
                                        <li><a href="dashboard-my-active-bids.html">My Active Bids</a></li>
                                        <li><a href="dashboard-post-a-task.html">Post a Task</a></li>
                                    </ul>
                                </li>
                                <li><a href="dashboard-settings.html">Settings</a></li>
                            </ul>
                        </li>

                        <li><a href="#">Pages</a>
                            <ul class="dropdown-nav">
                                <li><a href="pages-blog.html">Blog</a></li>
                                <li><a href="pages-pricing-plans.html">Pricing Plans</a></li>
                                <li><a href="pages-checkout-page.html">Checkout Page</a></li>
                                <li><a href="pages-invoice-template.html">Invoice Template</a></li>
                                <li><a href="pages-user-interface-elements.html">User Interface Elements</a></li>
                                <li><a href="pages-icons-cheatsheet.html">Icons Cheatsheet</a></li>
                                <li><a href="pages-contact.html">Contact</a></li>
                            </ul>
                        </li>

                    </ul>
                </nav>
                <div class="clearfix"></div>
                <!-- Main Navigation / End -->
                
            </div>
            <!-- Left Side Content / End -->


            <!-- Right Side Content / End -->
            <div class="right-side">

            @if( Auth::check() )    
                <!--  User Notifications -->
                <div class="header-widget hide-on-mobile">
                    
                    <!-- Notifications -->
                    <div class="header-notifications">

                        <!-- Trigger -->

                        <div class="header-notifications-trigger">
                            <span class="notify " id="noti_connect" style="display: none;"></span>
                            <a href="{{ URL::to('notifications') }}"><i class="icon-feather-bell"></i>@if( $notifications_count != 0 )<span> {{ $notifications_count }} </span>@endif</a>
                        </div>

                        <!-- Dropdown -->
                        <?php  /*<div class="header-notifications-dropdown">

                            <div class="header-notifications-headline">
                                <h4>Notifications</h4>
                                <button class="mark-as-read ripple-effect-dark" title="Mark all as read" data-tippy-placement="left">
                                    <i class="icon-feather-check-square"></i>
                                </button>
                            </div>

                            <div class="header-notifications-content">
                                <div class="header-notifications-scroll" data-simplebar>
                                    <ul>
                                        <!-- Notification -->
                                        <li class="notifications-not-read">
                                            <a href="dashboard-manage-candidates.html">
                                                <span class="notification-icon"><i class="icon-material-outline-group"></i></span>
                                                <span class="notification-text">
                                                    <strong>Michael Shannah</strong> applied for a job <span class="color">Full Stack Software Engineer</span>
                                                </span>
                                            </a>
                                        </li>

                                        <!-- Notification -->
                                        <li>
                                            <a href="dashboard-manage-bidders.html">
                                                <span class="notification-icon"><i class=" icon-material-outline-gavel"></i></span>
                                                <span class="notification-text">
                                                    <strong>Gilbert Allanis</strong> placed a bid on your <span class="color">iOS App Development</span> project
                                                </span>
                                            </a>
                                        </li>

                                        <!-- Notification -->
                                        <li>
                                            <a href="dashboard-manage-jobs.html">
                                                <span class="notification-icon"><i class="icon-material-outline-autorenew"></i></span>
                                                <span class="notification-text">
                                                    Your job listing <span class="color">Full Stack PHP Developer</span> is expiring.
                                                </span>
                                            </a>
                                        </li>

                                        <!-- Notification -->
                                        <li>
                                            <a href="dashboard-manage-candidates.html">
                                                <span class="notification-icon"><i class="icon-material-outline-group"></i></span>
                                                <span class="notification-text">
                                                    <strong>Sindy Forrest</strong> applied for a job <span class="color">Full Stack Software Engineer</span>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>*/ ?>

                    </div> 
                    
                    <!-- Messages -->
                    <div class="header-notifications">
                        <div class="header-notifications-trigger">
                            <a href="{{ URL::to('messages') }}"><i class="icon-feather-mail"></i>@if( $messages_count != 0 ) <span>{{ $messages_count }}</span>@endif</a>
                        </div>
                        <?php /*
                        <!-- Dropdown -->
                        <div class="header-notifications-dropdown">

                            <div class="header-notifications-headline">
                                <h4>Messages</h4>
                                <button class="mark-as-read ripple-effect-dark" title="Mark all as read" data-tippy-placement="left">
                                    <i class="icon-feather-check-square"></i>
                                </button>
                            </div>

                            <div class="header-notifications-content">
                                <div class="header-notifications-scroll" data-simplebar>
                                    <ul>
                                        <!-- Notification -->
                                        <li class="notifications-not-read">
                                            <a href="dashboard-messages.html">
                                                <span class="notification-avatar status-online"><img src="{{ asset('images/user-avatar-small-03.jpg') }}" alt=""></span>
                                                <div class="notification-text">
                                                    <strong>David Peterson</strong>
                                                    <p class="notification-msg-text">Thanks for reaching out. I'm quite busy right now on many...</p>
                                                    <span class="color">4 hours ago</span>
                                                </div>
                                            </a>
                                        </li>

                                        <!-- Notification -->
                                        <li class="notifications-not-read">
                                            <a href="dashboard-messages.html">
                                                <span class="notification-avatar status-offline"><img src="{{ asset('images/user-avatar-small-02.jpg') }}" alt=""></span>
                                                <div class="notification-text">
                                                    <strong>Sindy Forest</strong>
                                                    <p class="notification-msg-text">Hi Tom! Hate to break it to you, but I'm actually on vacation until...</p>
                                                    <span class="color">Yesterday</span>
                                                </div>
                                            </a>
                                        </li>

                                        <!-- Notification -->
                                        <li class="notifications-not-read">
                                            <a href="dashboard-messages.html">
                                                <span class="notification-avatar status-online"><img src="{{ asset('images/user-avatar-placeholder.png') }}" alt=""></span>
                                                <div class="notification-text">
                                                    <strong>Marcin Kowalski</strong>
                                                    <p class="notification-msg-text">I received payment. Thanks for cooperation!</p>
                                                    <span class="color">Yesterday</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <a href="dashboard-messages.html" class="header-notifications-button ripple-effect button-sliding-icon">View All Messages<i class="icon-material-outline-arrow-right-alt"></i></a>
                        </div>
                    </div>*/ ?>

                </div>
            <!-- User Menu -->
                <div class="header-widget">

                    <!-- Messages -->
                    <div class="header-notifications user-menu">
                        <div class="header-notifications-trigger user_profile_sub">
                           <?php /* <a href="#"><div class="user-avatar status-online">
                             @if(Auth::user()->user_image == '')
                                <img src="{{ asset('assets/images/default-user.png') }}" alt="user-img" width="36" class="img-circle">
                            @else
                                 <img src="{{ asset('uploads/users/'.Auth::user()->user_image) }}" alt="user-img" width="36" class="img-circle">
                            @endif


                            </div></a>*/ ?>
                             <a href="#"><div class="user-avatar status-online">
                             <img src="{{ asset('images/default-user.png') }}" alt="user-img" width="36" class="img-circle">
                        


                            </div></a>
                        </div>

                        <!-- Dropdown -->
                        <div class="header-notifications-dropdown">

                            <!-- User Status -->
                            <div class="user-status">

                                <!-- User Name / Avatar -->
                                <div class="user-details">
                                    <div class="user-avatar status-online">
                                        <img src="{{ asset('images/default-user.png') }}" alt="user-img" width="36" class="img-circle">
                               
                                    </div>
                                    <div class="user-name">
                                        {{Auth::user()->name}} 
                                        <span>
                                        @if(Auth::user()->role == 'admin')
                                            Admin
                                        @elseif(Auth::user()->role == 'provider')
                                            Employer
                                        @else
                                            Freelancer 
                                        @endif
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- User Status Switcher -->
                                <div class="status-switch" id="snackbar-user-status">
                                    <label class="user-online current-status">Online</label>
                                    <label class="user-invisible">Invisible</label>
                                    <!-- Status Indicator -->
                                    <span class="status-indicator" aria-hidden="true"></span>
                                </div>  
                        </div>

                        <ul class="user-menu-small-nav">
                         <?php /*   <li><a href="{{url('dashboard')}}"><i class="icon-material-outline-dashboard"></i> Dashboard</a></li>*/ ?>
                            <li><a href="{{ URL::to('account') }}"><i class="icon-material-outline-settings"></i> Settings</a></li>
                            <li><a href="{{ URL::to('session/logout') }}"><i class="icon-material-outline-power-settings-new"></i> Logout</a>

                             <form id="logout-form" action="" method="POST" style="display: none;">
                                @csrf
                            </form>
                             </li>
                        </ul>

                        </div>
                    </div>

                </div>
                <!-- User Menu / End -->

                <!-- Mobile Navigation Button -->
                <span class="mmenu-trigger">
                    <button class="hamburger hamburger--collapse" type="button">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </span>

            </div>
            
        
        {{-- SESSION NULL --}}  
        @else   
                <div class="header-widget">
                  
                    <a href="#sign-in-dialog" class="popup-with-zoom-anim log-in-button"><i class="icon-feather-log-in"></i> <span>Log In / Register</span></a>
                </div>

                <!-- Mobile Navigation Button -->
                <span class="mmenu-trigger">
                    <button class="hamburger hamburger--collapse" type="button">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </span>
        
        @endif  
                

        </div>
    </div>
    <!-- Header / End -->


</header>
<div class="clearfix"></div>
<!-- Header Container / End -->