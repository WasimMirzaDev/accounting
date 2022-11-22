
<style media="screen">
  #mydatatable_filter{
    display:none !important;
  }
</style>
<form method="post" id="event_form" action="{{route('events.create')}}">
<div class="modal-content">
   <div class="modal-header" style="color:white; background:#353D4B;">
      <button type="button" class="close" data-dismiss="modal" style="color:white;">&times;</button>
      <h4 class="modal-title">Event Management</h4>
   </div>
   <div class="modal-body" style="height:450px; overflow-y:scroll;">
      <div class="container">
         <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#manage_event">Event Detail</a></li>
            <li><a data-toggle="tab" href="#manage_student">Students</a></li>
         </ul>
         <div class="tab-content">
            <div id="manage_event" class="tab-pane fade in active">
                         <br>
               <div class="row">
                  <div class="col col-md-6">
                     <b>Start:</b><small style="color:red;">*</small>
                     <input type="hidden" name="id" value="{{$id}}">
                     <input type="datetime-local" class="form-control" onchange="change_date(this.value)" name="start" id="start" value="{{date('Y-m-d\TH:i:s', strtotime($start)) }}">
                  </div>
                  <div class="col col-md-6">
                     <b>End:</b><small style="color:red;">*</small>
                     <input type="datetime-local" class="form-control" name="end" id="end" value="{{date('Y-m-d\TH:i:s', strtotime($end)) }}">
                  </div>
               </div>
               <div class="row" style="margin-top:10px;">
                  <div class="col col-md-6">
                     <b>Title:</b><small style="color:red;">*</small>
                     <input type="text" class="form-control" name="title" id="title" value="{{$title}}">
                  </div>
                  <div class="col col-md-6">
                     <b>Status:</b><small style="color:red;">*</small>
                     <select class="form-control" name="stat_id">
                        <option value="">Select</option>
                        @if(!empty($status))
                        @foreach($status as $s)
                        <option {{!empty($stat_id) && $stat_id == $s->id ? 'selected' : ''}} value="{{$s->id}}">{{$s->name}}</option>
                        @endforeach
                        @endif
                     </select>
                  </div>
               </div>
               @if(auth()->user()->role == 1)
                <div class="row" style="margin-top:10px;">
                  <div class="col col-md-6">
                     <b>Dojo:</b><small style="color:red;">*</small>
                     <select class="form-control" onchange="" name="dojo">
                       <option value="">Select</option>
                       @if(!empty($dojos))
                         @foreach($dojos as $dojo)
                           <option   {{!empty($dojo_id) && $dojo_id == $dojo->id ? 'selected' : '' }}  value="{{$dojo->id}}">{{$dojo->name}}</option>
                         @endforeach
                       @endif
                      </select>
                   </div>
                  </div>
                  @endif
               <div class="row" style="margin-top:10px;">
                  <div class="col col-md-12">
                    <input type="checkbox" {{!empty($allDay) && $allDay == 'true' ? 'checked' : '' }} name="allDay" value="true">
                     <b>All Day</b>
                  </div>
               </div>
               <div class="row" style="margin-top:10px;">
                  <div class="col col-md-12">
                     <b>Note:</b>
                     <textarea class="form-control" rows="3" name="note" rows="8" cols="80">{{$note}}</textarea>
                  </div>
               </div>
            </div>
            <div id="manage_student" class="tab-pane fade">
              @if(!empty($list[0]))
              <table id="mydatatable" class="display table table-striped table-bordered" width="100%">
                 <thead>
                    <tr>
                      <th></th>
                       <th class="hasinput">
                          <input type="text" class="form-control" placeholder="">
                       </th>
                       <th class="hasinput">
                          <input type="text" class="form-control" placeholder="">
                       </th>
                       <th class="hasinput">
                          <input type="text" class="form-control" placeholder="">
                       </th>
                       <th class="hasinput">
                          <input type="text" class="form-control" placeholder="">
                       </th>
                    </tr>
                    <tr>
                      <th><input type="checkbox" name="all_student" onclick="check_all(this)" id="all_student" value=""></th>
                       <th>First Name</th>
                       <th>Last Name</th>
                       <th>Student Id</th>
                       <th>Dojo</th>
                    </tr>
                 </thead>
                 <tbody>

                      @php
                      $sr = 1
                      @endphp
                      @foreach($list as $l)
                      <tr id="row_{{$l->id}}">
                        <td><input type="checkbox" {{ in_array($l->id, $event_students) ? 'checked' : ''}} name="student_id[]" class="student_id" value="{{$l->id}}"> </td>
                         <td>{{$l->firstname}}</td>
                         <td>{{$l->lastname}}</td>
                         <td>{{!empty($l->user->login) ? $l->user->login : ''}}</td>
                         <td>{{!empty($l->dojo->name) ? $l->dojo->name : ''}}</td>
                      </tr>
                      @endforeach
                 </tbody>
              </table>
              @endif

              @if(empty($list[0]))
              <br/>
              <div class="alert alert-info">
                No students found..!
              </div>
              @endif

            </div>
         </div>
      </div>
   </div>
   <div class="modal-footer">
      <button type="submit" onclick="saveEvent()" class="btn btn-success">Save</button>
      @if($id > 0)<button type="button" onclick="deleteEvent({{$id}})" class="btn btn-danger">Delete</button>@endif
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   </div>
</div>
</form>
