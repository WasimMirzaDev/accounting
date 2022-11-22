@extends('layouts.app')

@section('content')
@php
$route_prefix = "attendance.";
@endphp
<style media="screen">
#attendance_daily_menu{
  color:white !important;
}

label, .col {
  color:black;
}

</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

<section id="widget-grid" class="">
    <div class="row">
       <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
          <div class="jarviswidget jarviswidget-sortable" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
             <header role="heading">
                <div class="jarviswidget-ctrls" role="menu">   <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus"></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div>
                <span class="widget-icon"> <i class="fa fa-check txt-color-green"></i> </span>
                <h2>Daily-Attendance</h2>
                <span class="jarviswidget-loader" style="display: none;"><i class="fa fa-refresh fa-spin"></i></span>
             </header>
             <div role="content" style="display: block;">
                <div class="jarviswidget-editbox">
                </div>
                <div class="widget-body no-padding">
                  <form method="post" target="_blank" id="attendance_form" autocomplete="off" enctype="multipart/form-data" action="{{route($route_prefix.'daily-report')}}">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
                     <input type="hidden" name="id" value="{{!empty($d->id) ? $d->id : 0}}">
                     <input type="hidden" id="route_prefix" name="" value="{{url('attendance/')}}">
                     <div class="container" style="margin-top:20px; margin-left:20px;">
                       <fieldset>
                         <div class="row" style="margin-top:5px;">
                           <section class="col col-md-2">
                               <input type="text" style="padding:20px; font-size:20px;" class="mydatepicker form-control" name="att_date" value="{{date('d-m-Y')}}">
                           </section>
                           <section class="col col-md-2">
                             <button type="submit" style="padding:12px;" class="btn btn-success" name="button">Show Attendance</button>
                           </section>
                         </div>
                       </fieldset>
                     </div>
                  </form>
                </div>
             </div>
          </div>
       </article>
    </div>
 </section>


@endsection
