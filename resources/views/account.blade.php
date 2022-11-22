@extends('layouts.app')

@section('content')
@php
$route_prefix = "account.";
@endphp
<style media="screen">
#dojo_menu{
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
                <h2>Account</h2>
                <span class="jarviswidget-loader" style="display: none;"><i class="fa fa-refresh fa-spin"></i></span>
             </header>
             <div role="content" style="display: block;">
                <div class="jarviswidget-editbox">
                </div>
                <div class="widget-body no-padding">
                  <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" id="addnew" href="#add"> {{empty($r) ? 'Add New' : 'Edit'}}</a></li>
                   @if(empty($d->id))
                    <li class=""><a data-toggle="tab" href="#list">List</a></li>
                    @endif
                  </ul>
                   <div class="tab-content">
                      <div id="add" class="tab-pane fade in active ">
                         <form method="post" id="dataForm2" class="smart-form" autocomplete="off" enctype="multipart/form-data" action="{{route($route_prefix.'save')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{!empty($d->id) ? $d->id : 0}}">
                            <input type="hidden" id="route_prefix" name="" value="{{url('account/')}}">
                            <fieldset>
                              @if (!empty($errors->any()))
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
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
                              <div class="row">
                                <section class="col col-2">
                                    <span class="input-group-btn" style="display:none;">
                                    <span class="btn btn-default btn-file">
                                       Browse… <input type="file" name="file" style="display:none;" id="imgInp" onchange="readURL(this);">
                                    </span>
                                    </span>
                                 <input type="text" id="browse_img" class="form-control bg-white" style="display:none;" name="" value="" disabled>
                                 <label for="imgInp" class="col-lg-4 text-center" style="color:black;width:110px; height:120px; display:block;">
                                    <img id="blah" src="{{!empty($d->id) && !empty($d->profile_pic) ? asset('/uploads/accounts/'.$d->id.$d->profile_pic) : asset('/app_images/default.jpg')}}" alt="" style="=cursor:pointer;max-width:100%;max-height:100%;" class="img-fluid"/>
                                    Profile Picture (jpg/png)
                               </label>
                             </section>

                            <section class="col-md-7">
                              <div class="row">
                                <section class="col col-6">
                                  Account Number: <small style="color:red;">*</small>
                                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                    <input type="text" autocomplete="off" name="acc_num" value="{{!empty($d->id) ? $d->acc_num : old('acc_num')}}" xplaceholder="Account Number">
                                  </label>
                                </section>
                                <section class="col col-6">
                                  Account Name: <small style="color:red;">*</small>
                                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                    <input type="text" autocomplete="off" name="acc_name" value="{{!empty($d->id) ? $d->acc_name : old('acc_name')}}" xplaceholder="Account Name">
                                  </label>
                                </section>
                              </div>
                              <div class="row">
                                <section class="col col-6">
                                   <label for="name" class="label">Account Type:<span style="color:red;">*</span></label>
                                   <select class="select2" name="level2_id">
                                     <option value="">Select Account Type</option>
                                     @if(!empty($level2))
                                       @foreach($level2 as $l2)
                                         <option {{isset($d->level2_id) && $d->level2_id == $l2->id ? 'selected' : ''}} value="{{$l2->id}}">{{$l2->name}}</option>
                                       @endforeach
                                     @endif
                                   </select>
                                </section>
                              </div>
                              <div class="row">
                                <section class="col col-6">
                                  City Code:
                                  <label class="input"> <i class="icon-prepend fa fa-map"></i>
                                    <input type="text" autocomplete="off" value="{{!empty($d->id) ? $d->city_code : old('city_code')}}" name="city_code" xplaceholder="City Code">
                                  </label>
                                </section>
                                <section class="col col-6">
                                  Care Of:
                                  <label class="input">
                                    <input type="text" autocomplete="off" value="{{!empty($d->id) ? $d->care_of : old('care_of')}}" name="care_of" xplaceholder="Care of">
                                  </label>
                                </section>
                              </div>
                              <div class="row">
                                <section class="col col-6">
                                  Mobile 1:
                                  <label class="input"> <i class="icon-prepend fa fa-mobile"></i>
                                    <input type="text"  autocomplete="off" name="mobile1" value="{{!empty($d->id) ? $d->mobile1 : old('mobile1')}}" xplaceholder="Mobile 1">
                                  </label>
                                </section>
                                <section class="col col-6">
                                  Mobile 2:
                                  <label class="input"> <i class="icon-prepend fa fa-mobile"></i>
                                    <input type="text" autocomplete="new-password" name="mobile2" value="{{!empty($d->id) ? $d->mobile2 : old('mobile2')}}" xplaceholder="Mobile 2">
                                  </label>
                                </section>
                              </div>
                              <div class="row">
                                <section class="col col-6">
                                  Ptcl 1:
                                  <label class="input"><i class="icon-prepend fa fa-phone"></i>
                                    <input type="text" autocomplete="off" name="ptcl1" value="{{!empty($d->id) ? $d->ptcl1 : old('ptcl1')}}" xplaceholder="Ptcl 1">
                                  </label>
                                </section>

                                <section class="col col-6">
                                  Ptcl 2:
                                  <label class="input" autocomplete="off"><i class="icon-prepend fa fa-phone"></i>
                                    <input type="text" autocomplete="off" name="ptcl2" value="{{!empty($d->id) ? $d->ptcl2 : old('ptcl2')}}" xplaceholder="Ptcl 2">
                                  </label>
                                </section>


                              </div>
                              <div class="row" style="display:none;">
                                <section class="col col-6">
                                  Visiting Card 1:
                                  <label class="input">
                                    <input type="text" autocomplete="off" name="vc1" value="{{!empty($d->id) ? $d->vc1 : old('vc1')}}" xplaceholder="Visiting Card 1">
                                  </label>
                                </section>

                                <section class="col col-6">
                                  Visiting Card 2:
                                  <label class="input">
                                    <input type="text" autocomplete="off" name="vc2" value="{{!empty($d->id) ? $d->vc2 : old('vc2')}}" xplaceholder="Visiting Card 2">
                                  </label>
                                </section>
                              </div>
                              <div class="row">
                                <section class="col col-6">
                                  Opening Debit:
                                  <label class="input">
                                    <input type="number" autocomplete="off" name="opening_dr" value="{{!empty($d->id) ? $d->opening_dr : old('opening_dr')}}" xplaceholder="Opening Debit">
                                  </label>
                                </section>
                                <section class="col col-6">
                                  Opening Credit:
                                  <label class="input">
                                    <input type="number" autocomplete="off" name="opening_cr" value="{{!empty($d->id) ? $d->opening_cr : old('opening_cr')}}" xplaceholder="Opening Credit">
                                  </label>
                                </section>
                              </div>
                              <div class="row">
                                <section class="col col-6">
                                  Map Address:
                                  <label class="input" autocomplete="off">
                                    <input type="text" autocomplete="off" name="map_address" value="{{!empty($d->id) ? $d->map_address : old('map_address')}}" xplaceholder="Map Address">
                                  </label>
                                </section>
                                <section class="col col-md-6" style="margin-top:20px;">
                                  <div class="inline-group">
                                    <label class="radio">
                                      <input checked {{!empty($d->id) && $d->active == '1' ? 'checked' : ''}} id="active" type="radio" name="active"  value="1">
                                      <i></i>Active
                                    </label>

                                    <label class="radio">
                                      <input {{!empty($d->id) && $d->active == '0' ? 'checked' : ''}} id="inactive" type="radio" name="active" value="0">
                                      <i></i>Inactive
                                    </label>
                                  </div>
                                </section>
                              </div>
                              <section>
                                Address:
                                <label class="textarea">
                                  <textarea rows="3" autocomplete="off" name="address" xplaceholder="Address">{{!empty($d->id) ? $d->address : old('address')}}</textarea>
                                </label>
                              </section>
                             </section>
                            </div>
                            </fieldset>

                            <footer>
                              <button type="submit" id="save_btn" class="btn btn-success">
                              Save
                              </button>
                               <a href="{{route('account.show')}}"
                               id="save_btn" class="btn btn-primary">
                               Cancel
                             </a>
                            </footer>
                         </form>
                      </div>
                      <div id="list" class="tab-pane">
                         <table id="datatable_fixed_column" class="display table table-striped table-bordered" width="100%">
                            <thead>
                               <tr>
                                  <th class="hasinput">
                                     <input type="text" class="form-control" xplaceholder="" />
                                  </th>
                                  <th class="hasinput">
                                     <input type="text" class="form-control" xplaceholder="" />
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" xplaceholder="" type="text">
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" xplaceholder="" type="text">
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" xplaceholder="" type="text">
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" xplaceholder="" type="text">
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" xplaceholder="" type="text">
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" xplaceholder="" type="text">
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" xplaceholder="" type="text">
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" xplaceholder="" type="text">
                                  </th>
                                  <th></th>
                                  <th></th>
                               </tr>
                               <tr>
                                  <th>Acc Num</th>
                                  <th>Acc Name</th>
                                  <th>Opening Debit</th>
                                  <th>Opening Credit</th>
                                  <th>Account Type</th>
                                  <th>City Code</th>
                                  <th>Care of</th>
                                  <th>Mobile 1</th>
                                  <th>Ptcl 1</th>
                                  <th>Status</th>
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
                                    <td>{{$l->acc_num}}</td>
                                    <td>{{$l->acc_name}}</td>
                                    <td>{{$l->opening_dr}}</td>
                                    <td>{{$l->opening_cr}}</td>
                                    <td>{{$l->account_type->name}}</td>
                                    <td>{{$l->city_code}}</td>
                                    <td>{{$l->care_of}}</td>
                                    <td>{{$l->mobile1}}</td>
                                    <td>{{$l->ptcl1}}</td>
                                    <td>{{$l->active == '1' ? 'Active' : 'Inactive'}}</td>
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
