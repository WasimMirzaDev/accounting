@extends('layouts.app')
@section('content')
@php
$route_prefix = "voucher.";
@endphp
<style media="screen">
#dojo_menu{
  color:white !important;
}

label, .col {
  color:black;
}

.readonly{
  background-color:gainsboro !important;
  margin-top:6px;
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
                <h2>Voucher</h2>
                <span class="jarviswidget-loader" style="display: none;"><i class="fa fa-refresh fa-spin"></i></span>
             </header>
             <div role="content" style="display: block;">
                <div class="jarviswidget-editbox">
                </div>
                <div class="widget-body no-padding">
                  <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" id="addnew" href="#add"> {{empty($r) ? 'Add New' : 'Edit'}}</a></li>
                   @if(empty($d->id))
                    <!-- <li class=""><a data-toggle="tab" href="#list">Receipts and Payments</a></li> -->
                    @endif
                  </ul>
                   <div class="tab-content">
                      <div id="add" class="tab-pane fade in active ">


                        <!-- Modal -->
                          <div class="modal fade" id="headModal" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title">Add Transaction Head</h4>
                                </div>
                                <div class="modal-body">
                                  <form class="smart-form" method="post" action="{{route('voucher.headtype.save')}}">
                                    @csrf
                                  <div class="row">
                                    <section class="col col-6">
                                      Date: <small style="color:red;">*</small>
                                      <label class="input">
                                        <input type="text" class="datepicker" autocomplete="off" name="gl_date" value="{{!empty($d->id) ? $d->gl_date : old('gl_date')}}">
                                      </label>
                                    </section>
                                    <section class="col col-6">
                                       <label for="name" class="label">Transaction Head Type:<span style="color:red;">*</span></label>
                                       <select class="select2" name="ht_id">
                                         <option value="">Select Head Type</option>
                                         @if(!empty($htype))
                                           @foreach($htype as $ht)
                                             <option value="{{$ht->id}}">{{$ht->name}}</option>
                                           @endforeach
                                         @endif
                                       </select>
                                    </section>
                                  </div>

                                  <div class="row">
                                    <section class="col col-6">
                                      Voucher #:
                                      <label class="input">
                                        <input type="text" class="readonly" autocomplete="off" name="vch_num" value="{{$voucher_id}}" placeholder="" readonly>
                                      </label>
                                    </section>
                                    <section class="col col-6">
                                      Folio:
                                      <label class="input">
                                        <input type="text" autocomplete="off" name="gl_folio" value="{{!empty($d->id) ? $d->gl_folio : old('gl_folio')}}" placeholder="">
                                      </label>
                                    </section>
                                  </div>
                                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float:right; padding:6px 12px; margin-left:6px;">Close</button>
                                  &nbsp;&nbsp;
                                  <button type="submit" class="btn btn-success" style="float:right; padding:6px 12px;">Save</button>
                                </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        <!-- end modal -->
                         <form method="post" id="dataForm2" class="smart-form" autocomplete="off" enctype="multipart/form-data" action="{{route($route_prefix.'save')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{!empty($d->id) ? $d->id : 0}}">
                            <input type="hidden" id="route_prefix" name="" value="{{url('account/')}}">
                            <fieldset>
                              @if (!empty($errors->any()))
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                              @endif

                          @if ($message = Session::get('success'))
                               <div class="alert alert-success alert-dismissible" role="alert">
                                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                   <p>{{ $message }}</p>
                               </div>
                           @endif
                          <div class="row">
                            <section class="col col-md-12">
                              <div class="row">
                                <section class="col col-2">
                                  <button type="button" class="btn btn-info" onclick="show_head_modal()" name="button" style="padding:8px 16px"><i class="fa fa-lg fa-plus"></i> Add Head</button>
                                </section>
                                <section class="col col-3">
                                   <label for="name" class="label">Search Voucher #:</label>
                                   <select class="select2" name="gl_id" onchange="fetch_vch_detail(this)">
                                     <option value="">Select Voucher</option>
                                     @if(!empty($vchs))
                                       @foreach($vchs as $v)
                                         <option myvch_num="{{$v->vch_num}}" @if($v->id == old('gl_id')) {{'selected'}} @endif myhead_type="{{$v->head_type->name}}" mygl_date="{{date('d/m/Y', strtotime($v->gl_date))}}" value="{{$v->id}}">{{$v->vch_num}} | {{$v->head_type->name}} | {{date('d/m/Y', strtotime($v->gl_date))}}</option>
                                       @endforeach
                                     @endif
                                   </select>
                                </section>
                                <section class="col col-2">
                                  Date
                                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                    <input type="text" class="readonly" autocomplete="off" name="vch_selected_date" value="{{old('vch_selected_date')}}" placeholder="" readonly id="vch_selected_date">
                                  </label>
                                </section>
                                <section class="col col-2">
                                  Voucher #
                                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                    <input type="text" class="readonly" autocomplete="off" name="vch_selected_num" value="{{old('vch_selected_num')}}" placeholder="" readonly id="vch_selected_num">
                                  </label>
                                </section>
                                <section class="col col-3">
                                  Head Type
                                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                    <input type="text" class="readonly" autocomplete="off" name="head_selected_type" value="{{old('head_selected_type')}}" placeholder="" readonly id="head_selected_type">
                                  </label>
                                </section>
                              </div>
                              <hr>
                              <br><br>


                              <div class="row">
                                <section class="col col-3">
                                   <label for="name" class="label">Voucher Type:</label>
                                   <select class="select2" name="vt_id">
                                     <option value="">Select Type</option>
                                     @if(!empty($vchtype))
                                       @foreach($vchtype as $vt)
                                         <option @if($vt->id == old('vt_id')) {{'selected'}} @endif value="{{$vt->id}}">{{$vt->name}}</option>
                                       @endforeach
                                     @endif
                                   </select>
                                </section>

                                <section class="col col-3">
                                   <label for="name" class="label">Account Name:</label>
                                   <select class="select2" name="level3_id" onchange="fetch_acc_detail(this)">
                                     <option value="">Select Account</option>
                                     @if(!empty($acc_d))
                                       @foreach($acc_d as $acc)
                                         <option myacc_num="{{$acc->acc_num}}" @if($acc->id == old('level3_id')) {{'selected'}} @endif myacc_type="{{$acc->account_type->name}}" value="{{$acc->id}}">
                                           {{$acc->acc_name}}
                                         </option>
                                       @endforeach
                                     @endif
                                   </select>
                                </section>

                                <!-- <section class="col col-1">
                                  Account #
                                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                    <input type="text" class="readonly" autocomplete="off" name="acc_selected_num" value="{{old('acc_selected_num')}}" placeholder="" readonly id="acc_selected_num">
                                  </label>
                                </section> -->
                                <!-- <section class="col col-2">
                                  Account Type
                                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                    <input type="text" class="readonly" autocomplete="off" name="acc_selected_type" value="{{old('acc_selected_type')}}" placeholder="" readonly id="acc_selected_type">
                                  </label>
                                </section> -->
                                <section class="col col-2" style="margin-top: 5px;">
                                       Doc Num:
                                       <label class="input">
                                         <input type="text" autocomplete="off" value="{{!empty($d->id) ? $d->doc_num : old('doc_num')}}" name="doc_num">
                                       </label>
                                </section>
                                <section class="col col-2" style="margin-top: 5px;">
                                       Doc Date:
                                       <label class="input">
                                         <input type="text" class="datepicker" autocomplete="off" value="{{!empty($d->id) ? $d->doc_date : old('doc_date')}}" name="doc_date">
                                       </label>
                                </section>
                                <section class="col col-2" style="margin-top: 5px;">
                                       Amount:
                                       <label class="input">
                                         <input type="number" autocomplete="off" value="{{!empty($d->id) ? $d->amount : old('amount')}}" name="amount">
                                       </label>
                                     </section>
                              </div>
                              <div class="row">
                                <!-- <div class="col col-2">
                                   <div class="row">



                                   </div>
                                </div> -->
                                <!-- <div class="col col-12"> -->
                                  <!-- <div class="row"> -->
                                    <section class="col col-10" style="width:100%">
                                      Description:
                                      <label class="textarea">
                                        <textarea name="description" cols="100" rows="2">{{!empty($d->id) ? $d->description : old('description')}}</textarea>
                                      </label>
                                    </section>

                                  <!-- </div> -->
                                <!-- </div> -->
                              </div>

                              <div class="row">

                                <section class="col col-4">
                                   <label for="name" class="label">Posting Account Details:</label>
                                   <select class="select2" name="posting_account">
                                     <option value="">Select Account</option>
                                     @if(!empty($acc_d))
                                       @foreach($acc_d as $acc)
                                         <option @if($acc->id == old('posting_account')) {{'selected'}} @endif myacc_num="{{$acc->acc_num}}" myacc_type="{{$acc->account_type->name}}" value="{{$acc->id}}">
                                           {{$acc->acc_name}}
                                         </option>
                                       @endforeach
                                     @endif
                                   </select>
                                </section>
                                <section class="col col-8">
                                      Narration:
                                      <label class="input">
                                        <input  name="narration" value="{{!empty($d->id) ? $d->narration : old('narration')}}" style="margin-top:6px">
                                      </label>
                                </section>
                                <!-- <section class="col col-2">
                                  Balance:
                                  <label class="input">
                                    <input type="text" class="readonly" autocomplete="off" id="balance" name="balance" value="{{old('balance')}}" readonly>
                                  </label>
                                </section> -->
                              </div>
                             </section>
                            </div>
                            </fieldset>
                            <footer>
                              <button type="submit" id="save_btn" class="btn btn-info" style="display:none;">
                              Post Voucher
                              </button>
                              <button type="submit" id="save_btn" class="btn btn-success">
                              Save
                              </button>
                               <a href="{{route('voucher.show')}}"
                               id="save_btn" class="btn btn-primary">
                               Cancel
                             </a>
                            </footer>
                            <div id="list" class="tab-pane">
                        <div class="row">
                          <div class="col col-md-6">
                            <div class="alert alert-info" style="font-size:20px; text-align:center;">
                              Receipts
                            </div>
                            <table id="datatable_fixed_column2" class="display table table-striped table-bordered" width="100%">
                               <thead>
                                  <tr>
                                     <th>Account</th>
                                     <th>DW</th>
                                     <th>Description</th>
                                     <th>Amount</th>
                                     <th>Doc#</th>
                                     <th>Doc Date</th>
                                     <th>Edit</th>
                                     <!-- <th>Delete</th> -->
                                  </tr>
                               </thead>
                               <tbody>
                                  @if(!empty($pays))
                                    @php $sr = 1
                                    @endphp
                                    @foreach($pays as $l)
                                    <tr id="row_{{$l->id}}">
                                       <td>{{$l->account_name->acc_name}}</td>
                                       <td>{{$l->voucher_type->short_name}}-{{$l->id}}</td>
                                       <td>{{$l->description}}</td>
                                       <td>{{$l->cr}}</td>
                                       <td>{{$l->doc_num}}</td>
                                       <td>{{$l->doc_date}}</td>
                                       <td><a id="edit_{{$l->id}}" href="{{route($route_prefix.'edit')}}/{{$l->id}}"     class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></a> </td>
                                       <!-- <td><button type="button" id="delete_{{$l->id}}" href="{{route($route_prefix.'delete')}}/{{$l->id}}" class="btn btn-danger btn-xs"  onclick="del({{$l->id}})">X</button> </td> -->
                                    </tr>
                                    @endforeach
                                  @endif
                               </tbody>
                            </table>
                          </div>

                          <div class="col col-md-6">
                            <div class="alert alert-info" style="font-size:20px; text-align:center;">
                              Payments
                            </div>
                            <table id="datatable_fixed_column" class="display table table-striped table-bordered" width="100%">
                               <thead>
                                  <tr>
                                     <th>Account</th>
                                     <th>DW</th>
                                     <th>Description</th>
                                     <th>Amount</th>
                                     <th>Doc#</th>
                                     <th>Doc Date</th>
                                     <th>Edit</th>
                                     <!-- <th>Delete</th> -->
                                  </tr>
                               </thead>
                               <tbody>
                                  @if(!empty($list))
                                    @php $sr = 1
                                    @endphp
                                    @foreach($list as $l)
                                    <tr id="row_{{$l->id}}">
                                       <td>{{$l->account_name->acc_name}}</td>
                                       <td>{{$l->voucher_type->short_name}}-{{$l->id}}</td>
                                       <td>{{$l->description}}</td>
                                       <td>{{$l->dr}}</td>
                                       <td>{{$l->doc_num}}</td>
                                       <td>{{$l->doc_date}}</td>
                                       <td><a id="edit_{{$l->id}}" href="{{route($route_prefix.'edit')}}/{{$l->id}}"     class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></a> </td>
                                       <!-- <td><button type="button" id="delete_{{$l->id}}" href="{{route($route_prefix.'delete')}}/{{$l->id}}" class="btn btn-danger btn-xs"  onclick="del({{$l->id}})">X</button> </td> -->
                                    </tr>
                                    @endforeach
                                  @endif
                               </tbody>
                            </table>
                          </div>
                        </div>

                      </div>

                         </form>
                      </div>

                   </div>
                </div>
             </div>
          </div>
       </article>
    </div>
 </section>


<script type="text/javascript">
  function show_head_modal()
  {
    $("#headModal").modal('show');
  }
  function fetch_vch_detail(vch)
  {
    let vsd = $('option:selected', $(vch)).attr('mygl_date');
    let vn = $('option:selected', $(vch)).attr('myvch_num');
    let ht = $('option:selected', $(vch)).attr('myhead_type');
    let gl_id = $('option:selected', $(vch)).val();
    $("#vch_selected_date").val(vsd);
    $("#vch_selected_num").val(vn);
    $("#head_selected_type").val(ht);
    $.ajax({
      url: "{{route('voucher.change')}}",
      method: 'POST',
      data:{_token:$('meta[name="csrf-token"]').attr('content'), vch_selected_date:vsd, vch_selected_num: vn, head_selected_type:ht, gl_id:gl_id},
      success: function(){
        location.reload();
      }
    })
  }
  function fetch_acc_detail(acc)
  {
    $("#acc_selected_num").val($('option:selected', $(acc)).attr('myacc_num'));
    $("#acc_selected_type").val($('option:selected', $(acc)).attr('myacc_type'));
  }
</script>

@endsection
