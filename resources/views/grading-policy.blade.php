@extends('layouts.app')

@section('content')
@php
$route_prefix = "grading-policy.";
@endphp
<style media="screen">
#attendance_ranking_menu{
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
                <h2>Attendance Ranking</h2>
                <span class="jarviswidget-loader" style="display: none;"><i class="fa fa-refresh fa-spin"></i></span>
             </header>
             <div role="content" style="display: block;">
                <div class="jarviswidget-editbox">
                </div>
                <div class="widget-body no-padding">
                  <ul class="nav nav-tabs">
                    <li class="{{empty($r->id) && empty(old('name')) ? 'active' : '' }}"><a data-toggle="tab" href="#list">List</a></li>
                     <li class="{{!empty($r->id) || !empty(old('name')) ? 'active' : '' }}"><a data-toggle="tab" id="addnew" href="#add">Add New</a></li>
                  </ul>
                   <div class="tab-content">
                      <div id="add" class="tab-pane fade {{!empty($r->id) || !empty(old('name')) ? 'in active' : '' }} ">
                         <form method="post" id="dataForm2" class="smart-form" autocomplete="off" enctype="multipart/form-data" action="{{route($route_prefix.'save')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{!empty($r->id) ? $r->id : 0}}">
                            <fieldset class="container">
                              <div class="row">
                            <section class="col col-md-7">
                              <div class="row">
                                <section class="col col-6">
                                  Rank Name: <small style="color:red;">*</small>
                                  <label class="input">
                                    <input type="text" autocomplete="off" name="grade" value="{{!empty($r->grade) ? $r->grade : old('grade')}}" placeholder="">
                                  </label>
                                </section>
                              </div>
                              <div class="row">
                                <section class="col col-6">
                                  From Absents:<small style="color:red;">*</small>
                                  <label class="input">
                                    <input type="number" autocomplete="off" name="from_value" value="{{!empty($r->grade) ? $r->from_value : old('from_value')}}" placeholder="">
                                  </label>
                                </section>
                                <section class="col col-6">
                                  To Absents:<small style="color:red;">*</small>
                                  <label class="input">
                                    <input type="number" autocomplete="off" value="{{!empty($r->grade) ? $r->to_value : old('to_value')}}" name="to_value" placeholder="">
                                  </label>
                                </section>
                              </div>
                             </section>
                            </div>
                            </fieldset>
                            @if (!empty(old('grade')))
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($message = Session::get('success'))
                             <div class="alert alert-success">
                                 <p>{{ $message }}</p>
                             </div>
                         @endif
                            <footer>
                              <button type="submit" id="save_btn" class="btn btn-success">
                              Save
                              </button>
                               <a href="{{route('grading-policy.show')}}"
                               id="save_btn" class="btn btn-primary">
                               Cancel
                             </a>
                            </footer>
                         </form>
                      </div>
                      <div id="list" class="tab-pane fade {{empty($r->id) && empty(old('name')) ? 'in active' : '' }}">
                         <table id="datatable_fixed_column" class="display table table-striped table-bordered" width="100%">
                            <thead>
                               <tr>
                                  <th class="hasinput">
                                     <input type="text" class="form-control" placeholder="" />
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" placeholder="" type="text">
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" placeholder="" type="text">
                                  </th>
                                  <th></th>
                                  <th></th>
                               </tr>
                               <tr>
                                  <th>Rank Name</th>
                                  <th>From Absents</th>
                                  <th>To Absents</th>
                                  <th>Edit</th>
                                  <th>Delete</th>
                               </tr>
                            </thead>
                            <tbody>
                               @if(!empty($list))
                                 @php $sr = 1
                                 @endphp
                                 @foreach($list as $l)
                                 <tr id="row_{{$l->id}}">
                                    <td>{{$l->grade}}</td>
                                    <td>{{$l->from_value}}</td>
                                    <td>{{$l->to_value}}</td>
                                    <td><a id="edit_{{$l->id}}" href="{{route($route_prefix.'edit')}}/{{$l->id}}"     class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></a> </td>
                                    <td><button type="button" id="delete_{{$l->id}}" href="{{route($route_prefix.'delete')}}/{{$l->id}}" class="btn btn-danger btn-xs"  onclick="del({{$l->id}})">X</button> </td>
                                 </tr>
                                 @endforeach
                               @endif
                            </tbody>
                         </table>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </article>
    </div>
 </section>


@endsection
