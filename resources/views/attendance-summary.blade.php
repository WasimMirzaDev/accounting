@extends('layouts.app')

@section('content')
@php
$route_prefix = "attendance.";
@endphp
<style media="screen">
#attendance_summary_menu{
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
                <h2>Attendance-Summary</h2>
                <span class="jarviswidget-loader" style="display: none;"><i class="fa fa-refresh fa-spin"></i></span>
             </header>
             <div role="content" style="display: block;">
                <div class="jarviswidget-editbox">
                </div>
                <div class="widget-body no-padding">
                  <form method="post" id="attendance_form" autocomplete="off" enctype="multipart/form-data" action="{{route($route_prefix.'attendance-summary')}}">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
                     <input type="hidden" name="id" value="{{!empty($d->id) ? $d->id : 0}}">
                     <input type="hidden" id="route_prefix" name="" value="{{url('attendance/')}}">
                     <div class="container" style="margin-top:20px; margin-left:20px;">
                       <fieldset>
                         <div class="row" style="margin-top:5px;">
                           <section class="col col-md-2">
                               <input type="text" style="padding:20px; font-size:20px;" class="mydatepicker form-control" name="datef" value="{{!empty($datef) ? date('d-m-Y', strtotime($datef)) : date('d-m-Y', strtotime('-1 month'))}}">
                           </section>
                           <section class="col col-md-2">
                               <input type="text" style="padding:20px; font-size:20px;" class="mydatepicker form-control" name="datet" value="{{!empty($datet) ? date('d-m-Y', strtotime($datet)) : date('d-m-Y')}}">
                           </section>
                           <section class="col col-md-2">
                             <button type="submit" style="padding:12px;" class="btn btn-success" name="button">Show Attendance</button>
                           </section>
                         </div>
                       </fieldset>
                     </div>
                  </form>
                  <br>
                  <div class="container-fluid">
                    @if(!empty($list))
                    <table id="datatable_fixed_column" class="display table table-striped table-bordered" width="100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th class="hasinput">
                            <input type="text" class="form-control" placeholder="" />
                          </th>
                          <th class="hasinput">
                            <input class="form-control" placeholder="" type="text">
                          </th>
                          <th class="hasinput">
                            <input class="form-control" placeholder="" type="text">
                          </th>
                          <th class="hasinput">
                            <input class="form-control" placeholder="" type="text">
                          </th>
                          <th class="hasinput">
                            <input class="form-control" placeholder="" type="text">
                          </th>
                          <th class="hasinput">
                            <input class="form-control" placeholder="" type="text">
                          </th>
                          <th class="hasinput">
                            <input class="form-control" placeholder="" type="text">
                          </th>
                        </tr>
                        <tr>
                          <td>Sr</td>
                          <td>Student Id</td>
                          <td>First Name</td>
                          <td>Last Name</td>
                          <td>Presents</td>
                          <td>Absents</td>
                          <td>Grade</td>
                          <td>Dojo Name</td>
                        </tr>
                      </thead>
                      <tbody>
                        @php $sr = 1
                        @endphp
                        @foreach($list as $l)
                        <tr>
                          <td>{{$sr++}}</td>
                          <td>{{$l->login}}</td>
                          <td>{{$l->firstname}}</td>
                          <td>{{$l->lastname}}</td>
                          <td>{{$l->presents}}</td>
                          <td>{{$l->absents}}</td>
                          <td>{{$l->grade}}</td>
                          <td>{{$l->dojo_name}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    @endif
                  </div>
                </div>
             </div>
          </div>
       </article>
    </div>
 </section>


@endsection
