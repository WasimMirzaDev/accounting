@extends('layouts.app')

@section('content')
@php
$route_prefix = "dojos.";
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
                <h2>Dojos</h2>
                <span class="jarviswidget-loader" style="display: none;"><i class="fa fa-refresh fa-spin"></i></span>
             </header>
             <div role="content" style="display: block;">
                <div class="jarviswidget-editbox">
                </div>
                <div class="widget-body no-padding">
                  <ul class="nav nav-tabs">
                    <li class="{{empty($d->id) && empty(old('name')) ? 'active' : '' }}"><a data-toggle="tab" href="#list">List</a></li>
                     <li class="{{!empty($d->id) || !empty(old('name')) ? 'active' : '' }}"><a data-toggle="tab" id="addnew" href="#add">Add New</a></li>
                  </ul>
                   <div class="tab-content">
                      <div id="add" class="tab-pane fade {{!empty($d->id) || !empty(old('name')) ? 'in active' : '' }} ">
                         <form method="post" id="dataForm2" class="smart-form" autocomplete="off" enctype="multipart/form-data" action="{{route($route_prefix.'save')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{!empty($d->id) ? $d->id : 0}}">
                            <input type="hidden" id="route_prefix" name="" value="{{url('dojos/')}}">
                            <fieldset>
                              <div class="row">
                                <section class="col col-2">
                                    <span class="input-group-btn" style="display:none;">
                                    <span class="btn btn-default btn-file">
                                       Browse… <input type="file" name="file" style="display:none;" id="imgInp" onchange="readURL(this);">
                                    </span>
                                    </span>
                                 <input type="text" id="browse_img" class="form-control bg-white" style="display:none;" name="" value="" disabled>
                                 <label for="imgInp" class="col-lg-4 text-center" style="color:black;width:110px; height:120px; display:block;"> 
                                    <img id="blah" src="{{!empty($d->id) && !empty($d->filetype) ? asset('/uploads/dojos/'.$d->id.$d->filetype) : asset('/app_images/default.jpg')}}" alt="" style="=cursor:pointer;max-width:100%;max-height:100%;" class="img-fluid"/>
                                    dojo Image (jpg/png)
                               </label>
                             </section>

                            <section class="col-md-7">
                              <div class="row">
                                <section class="col col-6">
                                  Name: <small style="color:red;">*</small>
                                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                    <input type="text" autocomplete="off" name="name" value="{{!empty($d->id) ? $d->name : old('name')}}" placeholder="Full name">
                                  </label>
                                </section>
                                <section class="col col-6">
                                  Owner:
                                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                    <input type="text" autocomplete="off" name="owner" value="{{!empty($d->id) ? $d->owner : old('owner')}}" placeholder="Owner">
                                  </label>
                                </section>
                              </div>
                              <div class="row">
                                <section class="col col-6">
                                  Email:<small style="color:red;">*</small>
                                  <label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
                                    <input type="email" autocomplete="off" value="{{!empty($d->id) ? $d->email : old('email')}}" name="email" placeholder="E-mail">
                                  </label>
                                </section>
                                <section class="col col-6">
                                  Phone:<small style="color:red;">*</small>
                                  <label class="input"> <i class="icon-prepend fa fa-phone"></i>
                                    <input type="text" autocomplete="off" value="{{!empty($d->id) ? $d->phone : old('phone')}}" name="phone" placeholder="Phone">
                                  </label>
                                </section>
                              </div>
                              <div class="row">
                                <section class="col col-6">
                                  Login:<small style="color:red;">*</small>
                                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                    <input type="text"  autocomplete="off" name="login" value="{{!empty($d->id) ? $d->user->login : old('login')}}" placeholder="Login">
                                  </label>
                                </section>
                                <section class="col col-6">
                                  Password:<small style="color:red;">*</small>
                                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                    <input type="password" autocomplete="new-password" name="password" value="" placeholder="Password">
                                  </label>
                                </section>
                              </div>
                              <div class="row">
                                <section class="col col-6">
                                  Country:
                                  <label class="input">
                                    <input type="text" autocomplete="off" name="country" value="{{!empty($d->id) ? $d->country : old('country')}}" placeholder="Country">
                                  </label>
                                </section>

                                <section class="col col-6">
                                  Province:
                                  <label class="input" autocomplete="off">
                                    <input type="text" autocomplete="off" name="province" value="{{!empty($d->id) ? $d->province : old('province')}}" placeholder="Province">
                                  </label>
                                </section>


                              </div>
                              <div class="row">
                                <section class="col col-6">
                                  City:
                                  <label class="input">
                                    <input type="text" autocomplete="off" name="city" value="{{!empty($d->id) ? $d->city : old('city')}}" placeholder="City">
                                  </label>
                                </section>

                                <section class="col col-6">
                                  Postal:
                                  <label class="input">
                                    <input type="text" autocomplete="off" name="postal" value="{{!empty($d->id) ? $d->postal : old('postal')}}" placeholder="Postal">
                                  </label>
                                </section>
                              </div>
                              <div class="row">
                                <section class="col col-6" style="display:none;">
                                  Identifier:<small style="color:red;">*</small>
                                  <label class="input" autocomplete="off">
                                    <input type="text" autocomplete="off" name="identifier" value="0" placeholder="Identifier">
                                  </label>
                                </section>
                                <section class="col col-md-4" style="margin-top:20px;">
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
                                  <textarea rows="3" autocomplete="off" name="address" placeholder="Address">{{!empty($d->id) ? $d->address : old('address')}}</textarea>
                                </label>
                              </section>
                             </section>
                            </div>
                            </fieldset>
                            @if (!empty(old('name')))
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
                               <a href="{{route('dojos.show')}}"
                               id="save_btn" class="btn btn-primary">
                               Cancel
                             </a>
                            </footer>
                         </form>
                      </div>
                      <div id="list" class="tab-pane fade {{empty($d->id) && empty(old('name')) ? 'in active' : '' }}">
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
                                  <th></th>
                                  <th></th>
                               </tr>
                               <tr>
                                  <th>Name</th>
                                  <th>Identifier</th>
                                  <th>Phone</th>
                                  <th>Email</th>
                                  <th>Country</th>
                                  <th>City</th>
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
                                    <td>{{$l->name}}</td>
                                    <td>{{$l->identifier}}</td>
                                    <td>{{$l->phone}}</td>
                                    <td>{{$l->email}}</td>
                                    <td>{{$l->country}}</td>
                                    <td>{{$l->city}}</td>
                                    <td>{{$l->user->active == '1' ? 'Active' : 'Inactive'}}</td>
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
