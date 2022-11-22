@extends('layouts.app')

@section('content')
@php
$route_prefix = "attendance.";
@endphp
<style media="screen">
#attendance_menu{
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
                <h2>Upload-Attendance</h2>
                <span class="jarviswidget-loader" style="display: none;"><i class="fa fa-refresh fa-spin"></i></span>
             </header>
             <div role="content" style="display: block;">
                <div class="jarviswidget-editbox">
                </div>
                <div class="widget-body no-padding">
                  <form method="post" id="attendance_form" class="smart-form" autocomplete="off" enctype="multipart/form-data" action="{{route($route_prefix.'save')}}">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
                     <input type="hidden" name="id" value="{{!empty($d->id) ? $d->id : 0}}">
                     <input type="hidden" id="route_prefix" name="" value="{{url('attendance/')}}">
                        <fieldset>
                          <div class="row" style="margin-top:5px;">
                             <section class="col col-6">
                               <label class="textarea">
                                 Choose File (CSV only)<input type="file" name="file" value="">
                               </label>
                             </section>
                          </div>
                        </fieldset>
                        <div class="row">
                          <div class="col col-md-12 col-lg-12 col-sm-12" id="response"> 
                          </div>
                        </div>
                        <footer>
                           <button type="submit" onclick="upload_attendance()" id="save_btn" class="btn btn-success">
                           Save
                           </button>
                        </footer>
                  </form>
                </div>
             </div>
          </div>
       </article>
    </div>
 </section>


@endsection
