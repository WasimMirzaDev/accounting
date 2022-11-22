@extends('layouts.app')

@section('content')
@php
$route_prefix = "admin.";
@endphp
<style media="screen">
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
                <h2>Profile Setting</h2>
                <span class="jarviswidget-loader" style="display: none;"><i class="fa fa-refresh fa-spin"></i></span>
             </header>
             <div role="content" style="display: block;">
                <div class="jarviswidget-editbox">
                </div>
                <div class="widget-body no-padding">
                  <form method="post" id="dataForm2" class="smart-form" autocomplete="off" enctype="multipart/form-data" action="{{route($route_prefix.'update-profile')}}">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                             <img id="blah" src="{{!empty($u->id) && !empty($u->filetype) ? asset('/uploads/admin/'.$u->id.$u->filetype) : asset('/app_images/default.jpg')}}" alt="" style="=cursor:pointer;max-width:100%;max-height:100%;" class="img-fluid"/>
                             User Image (jpg/png)
                        </label>
                      </section>

                     <section class="col-md-7">
                       <div class="row">
                         <section class="col col-6">
                           Name: <small style="color:red;">*</small>
                           <label class="input"> <i class="icon-prepend fa fa-user"></i>
                             <input type="text" autocomplete="off" name="name" value="{{!empty($u->id) ? $u->name : old('name')}}" placeholder="Full name">
                           </label>
                         </section>
                         <section class="col col-6">
                           Email:
                           <label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
                             <input type="email" autocomplete="off" value="{{!empty($u->id) ? $u->email : old('email')}}" name="email" placeholder="E-mail">
                           </label>
                         </section>
                       </div>

                       <div class="row">
                         <section class="col col-6">
                           Login:<small style="color:red;">*</small>
                           <label class="input"> <i class="icon-prepend fa fa-user"></i>
                             <input type="text"  autocomplete="off" name="login" value="{{!empty($u->id) ? $u->login : old('login')}}" placeholder="Login">
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

                         </section>

                         <section class="col col-6">
                           Confirm Password:<small style="color:red;">*</small>
                           <label class="input"> <i class="icon-prepend fa fa-user"></i>
                             <input type="password" autocomplete="new-password" name="password_confirmation" value="" placeholder="Confirm Password">
                           </label>
                         </section>
                       </div>
                     </fieldset>
                 @if ($message = Session::get('success'))
                      <div class="alert alert-success">
                          <p>{{ $message }}</p>
                      </div>
                  @endif

                  @if ($message = Session::get('errors'))
                       <div class="alert alert-danger">
                         <ul>
                           {{$errors}}
                         </ul>
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
             </div>
          </div>
       </article>
    </div>
 </section>
@endsection
