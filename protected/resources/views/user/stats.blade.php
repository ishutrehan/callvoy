@extends('layouts.master')

@section('title'){{ Lang::get('users.stats') }} - @stop

@section('css_style')
{{ HTML::style('public/css/morris.css') }}
{{ HTML::style('public/css/jquery.fs.picker.min.css') }}
@stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Data User logged ** //
     $user = Auth::user();
	 
	 // Shots
	 $shots    = Shots::where('user_id',Auth::user()->id)->count();
	 
	 // Comments
	 $comments = Comments::where('shots.user_id',Auth::user()->id)
	 ->leftjoin('shots','shots.id','=','comments.shots_id')->count();
	 
	  // Likes
	 $likes = Like::where('shots.user_id',Auth::user()->id)
	 ->where('likes.status',1)
	 ->leftjoin('shots','shots.id','=','likes.shots_id')->count();

     ?>
     
<div class="col-md-8">
     	
     	<div class="row row-margin-20">
    		<div class="col-md-4">
    			<h6 class="margin-zero text-center">
    				
    				<a class="btn btn-inverse btn-border btn-block text-shadow font-grid position-relative">
    					<span class="stats-icon">
    						<i class="icon-file myicon-right"></i>
    					</span>
    					{{number_format($shots)}}
    					<small class="btn-block sm-btn-size title-stats">{{Lang::get('misc.shots')}}</small>
    				</a>
    				</h6>
    		</div>
    		
    		<div class="col-md-4">
    			<h6 class="margin-zero text-center">
    				<a class="btn btn-danger btn-border btn-block text-shadow font-grid position-relative">
    					<span class="stats-icon">
    						<i class="icon-bubbles2 myicon-right"></i>
    					</span>
    					{{number_format($comments)}}
    					<small class="btn-block sm-btn-size title-stats">{{Lang::get('misc.comments')}}</small>
    				</a>
    				</h6>
    		</div>
    		
    		<div class="col-md-4">
    			<h6 class="margin-zero text-center">
    				<a class="btn btn-info btn-border btn-block text-shadow font-grid position-relative">
    					<span class="stats-icon">
    						<i class="icon-heart myicon-right"></i>
    					</span>
    					{{number_format($likes)}}
    					<small class="btn-block sm-btn-size title-stats">{{Lang::get('misc.likes')}}</small>
    				</a>
    				</h6>
    		</div>
    	</div>
    	
     	<!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<span class="btn-block">
		  		{{ Lang::get('misc.views_last_30_days') }}
		  	</span><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body" id="chart1"></div><!-- Panel Body -->
   </div><!-- Panel Default -->
   
   <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<span class="btn-block">
		  		{{ Lang::get('misc.likes_last_30_days') }}
		  	</span><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body" id="chart2"></div><!-- Panel Body -->
   </div><!-- Panel Default -->
   
</div><!-- col-md-8 -->

@stop

@section('sidebar')
<div class="col-md-4">
	
	@include('includes.user-card')
	
	@include('includes.sidebar_edit_user')

	@include('includes.ads')
          
          
          
</div><!-- /End col md-4 -->

@stop

@section('javascript')
  {{ HTML::script('public/js/raphael-min.js') }}
  {{ HTML::script('public/js/morris.min.js') }}
<script type="text/javascript">

var IndexToMonth = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];

//** Charts
new Morris.Area({ 
  // ID of the element in which to draw the chart.
  element: 'chart1',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    
    <?php 
    for ( $i=0; $i < 30; ++$i) { 
    	
		$date = date('Y-m-d', strtotime('today - '.$i.' days'));
		
		$visits = Visits::where('shots.user_id',Auth::user()->id)
		->whereRaw("DATE(visits.date) = '".$date."'")
		->leftjoin('shots','shots.id','=','visits.shots_id')->count();
		
		//print_r(DB::getQueryLog());
		
    	?>
    	
    { days: '<?php echo $date; ?>', value: <?php echo $visits ?> },
   
    <?php } ?>
  ],
  // The name of the data record attribute that contains x-values.
  xkey: 'days',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['value'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['{{Lang::get("misc.views")}}'],
  pointFillColors: ['#F40808'],
  lineColors: ['#DDD'],
  hideHover: 'auto',
  gridIntegers: true,
  resize: true,
  xLabelFormat: function (x) {
                  var month = IndexToMonth[ x.getMonth() ];
                  var year = x.getFullYear();
                  var day = x.getDate();
                  return  day +' ' + month;
                  //return  year + ' '+ day +' ' + month;
              },
          dateFormat: function (x) {
                  var month = IndexToMonth[ new Date(x).getMonth() ];
                  var year = new Date(x).getFullYear();
                  var day = new Date(x).getDate();
                  return day +' ' + month;
                  //return year + ' '+ day +' ' + month;
              },
  
   /*xLabelFormat: function(d) {
                    return d.getDate()+' '+monthNames[d.getMonth()]+' '+d.getFullYear(); 
                    },
                    
        hoverCallback: function(index, options, content) {
        var data = options.data[index];
       return content.xkey+'<br/>'+options.labels+' '+data.value;
    },*/
});

//** Charts
new Morris.Area({ 
  // ID of the element in which to draw the chart.
  element: 'chart2',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    
    <?php 
    for ( $i=0; $i < 30; ++$i) { 
    	
		$date = date('Y-m-d', strtotime('today - '.$i.' days'));
		
		$likes = Like::where('shots.user_id',Auth::user()->id)
		->where('likes.status',1)
		->whereRaw("DATE(likes.date) = '".$date."'")
		->leftjoin('shots','shots.id','=','likes.shots_id')->count();
		
		//print_r(DB::getQueryLog());
		
    	?>
    	
    { days: '<?php echo $date; ?>', value: <?php echo $likes ?> },
   
    <?php } ?>
  ],
  // The name of the data record attribute that contains x-values.
  xkey: 'days',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['value'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['{{Lang::get("misc.likes")}}'],
  hideHover: 'auto',
  gridIntegers: true,
  resize: true,
  xLabelFormat: function (x) {
                  var month = IndexToMonth[ x.getMonth() ];
                  var year = x.getFullYear();
                  var day = x.getDate();
                  return  day +' ' + month;
                  //return  year + ' '+ day +' ' + month;
              },
          dateFormat: function (x) {
                  var month = IndexToMonth[ new Date(x).getMonth() ];
                  var year = new Date(x).getFullYear();
                  var day = new Date(x).getDate();
                  return day +' ' + month;
                  //return year + ' '+ day +' ' + month;
              },
});

//** Changes Form **//
function changesForm () {
var button = $('#saveUpdate');
$('form.form-account input, select, textarea, checked').each(function () {
    $(this).data('val', $(this).val());
    $(this).data('checked', $(this).is(':checked'));
});

$('form.form-account input, select, textarea, checked').bind('keyup change blur', function(){
    var changed  = false;
    var ifChange = false;
    button.css({'opacity':'0.7','cursor':'default'});
    
    $('form.form-account input, select, textarea, checked').each(function () {
        if( trim( $(this).val() ) != $(this).data('val') || $(this).is(':checked') != $(this).data('checked') ){
            changed = true;
            ifChange = true;
            button.css({'opacity':'1','cursor':'pointer'})
        }
       
    });
    button.prop('disabled', !changed);
});
}//<<<--- Function
changesForm();

</script>
@stop
