@extends('layouts.app')
@section('content')
@php
$route_prefix = "events.";
@endphp
<style media="screen">
#events_menu{
  color:white !important;
}

     tr:hover {
          background: none !important;
        }
        .fc-time{
          display:inline-block !important;
        }
</style>

<section id="widget-grid" class="">
    <div class="row">
       <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
          <div class="jarviswidget jarviswidget-sortable" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">

             <div role="content" style="display: block;">
                <div class="jarviswidget-editbox">
                </div>
                <div class="widget-body no-padding">
                        <div class="row">

                          <div class="col-sm-9 col-md-9 col-lg-9">

                            <!-- new widget -->
                            <div class="jarviswidget jarviswidget-color-blueDark">

                              <!-- widget options:
                              usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

                              data-widget-colorbutton="false"
                              data-widget-editbutton="false"
                              data-widget-togglebutton="false"
                              data-widget-deletebutton="false"
                              data-widget-fullscreenbutton="false"
                              data-widget-custombutton="false"
                              data-widget-collapsed="true"
                              data-widget-sortable="false"

                              -->
                              <header>
                                <span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
                                <h2> My Events </h2>
                                <div class="widget-toolbar">
                                  <!-- add: non-hidden - to disable auto hide -->
                                  <div class="btn-group">
                                    <button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
                                      Showing <i class="fa fa-caret-down"></i>
                                    </button>
                                    <ul class="dropdown-menu js-status-update pull-right">
                                      <li>
                                        <a href="javascript:void(0);" id="mt">Month</a>
                                      </li>
                                      <li>
                                        <a href="javascript:void(0);" id="ag">Agenda</a>
                                      </li>
                                      <li>
                                        <a href="javascript:void(0);" id="td">Today</a>
                                      </li>
                                    </ul>
                                  </div>
                                </div>
                              </header>

                              <!-- widget div-->
                              <div>

                                <div class="widget-body no-padding">
                                  <!-- content goes here -->
                                  <div class="widget-body-toolbar">

                                    <div id="calendar-buttons">

                                      <div class="btn-group">
                                        <a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-prev"><i class="fa fa-chevron-left"></i></a>
                                        <a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-next"><i class="fa fa-chevron-right"></i></a>
                                      </div>
                                    </div>
                                  </div>
                                  <div id="calendar"></div>

                                  <!-- end content -->
                                </div>

                              </div>
                              <!-- end widget div -->
                            </div>
                            <!-- end widget -->

                          </div>
                          <br>
                          <br>
                          @if(auth()->user()->role == 1)
                          <div class="col-md-3">
                          <b>  Select Dojo: </b>
                            <select class="select2" onchange="refetch_events(this.value)">
                              <option value="">All</option>
                              @if(!empty($dojos))
                                @foreach($dojos as $dojo)
                                  <option value="{{$dojo->id}}">{{$dojo->name}}</option>
                                @endforeach
                              @endif
                            </select>
                          </div>
                          @endif

                        </div>



                        <!-- Modal -->
                        <div id="event_modal" class="modal" role="dialog" data-backdrop="static" data-keyboard="false" style="margin-top:-25px;">
                          <div class="modal-dialog" id="event_modal_detail">

                          </div>
                        </div>
                </div>
             </div>
          </div>
       </article>
    </div>
 </section>


@endsection
