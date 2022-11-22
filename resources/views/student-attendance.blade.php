@extends('layouts.app')

@section('content')


<style media="screen">
#student_attendance_menu{
  color:white !important;
}

label, .col {
  color:black;
}
#content{
    padding:0 !important;
}

body{
    background: url(http://mymaplist.com/img/parallax/back.png);
	background-color: #444;
    background: url(http://mymaplist.com/img/parallax/pinlayer2.png),url(http://mymaplist.com/img/parallax/pinlayer1.png),url(http://mymaplist.com/img/parallax/back.png);    
}

.vertical-offset-100{
    padding-top:25vh;
}


</style>



<script src="http://mymaplist.com/js/vendor/TweenLite.min.js"></script>
<!-- This is a very simple parallax effect achieved by simple CSS 3 multiple backgrounds, made by http://twitter.com/msurguy -->
<div style="background:url({{asset('/app_images/att-back.png')}}); height:100vh; background-position:center; background-size:cover;" >
<div class="container">
    <div class="row vertical-offset-100">
    	<div class="col-md-8 col-md-offset-2">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Please enter your student id</h3>
			 	</div>
			  	<div class="panel-body">
			    	<form accept-charset="UTF-8" role="form">
                    <fieldset>
			    	  	<div class="form-group">
			    		    <input class="form-control" autocomplete="off" autofocus name="student_id" id="student_id" type="text" style="font-size:22px; padding:20px; text-align:center;">
			    		</div>
			    		<div id="response">
			    		    
			    		</div>
			    		<button type="button" class="btn btn-lg btn-success btn-block" onclick="markAttendance()">OK</button>
			    	</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
	</div>
</div>
    
</div>






<script>
    $(document).ready(function(){
  $(document).mousemove(function(e){
     TweenLite.to($('body'), 
        .5, 
        { css: 
            {
                backgroundPosition: ""+ parseInt(event.pageX/8) + "px "+parseInt(event.pageY/'12')+"px, "+parseInt(event.pageX/'15')+"px "+parseInt(event.pageY/'15')+"px, "+parseInt(event.pageX/'30')+"px "+parseInt(event.pageY/'30')+"px"
            }
        });
  });
});


function markAttendance()
{
    var student_id = $("#student_id").val();
    $.ajax({
        url: "student_attendance",
        method: "post", 
        data: {student_id:student_id, mark_attendance:true},
        success: function(response)
        {
            response = JSON.parse(response);
            var cls = response['class'];
            var msg = response['msg'];
            var std = response['student'];
            var success = response['success'];
            if(std == null)
            {
              $("#response").html('<div class="'+cls+'">'+msg+'</div>');   
            }
            else 
            {
                $("#response").html('<div class="'+cls+'"> Name: '+std+' <br/>  '+msg+'</div>');    
            }
            if(success == 1)
            {
                setTimeout(function() { 
                $("#response").html("");
                $("#student_id").val("");
            }, 3000);   
            }
        }
    })
}
</script>





@endsection
