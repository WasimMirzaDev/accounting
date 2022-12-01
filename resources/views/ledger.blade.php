@extends('layouts.app')

@section('content')
@php
$route_prefix = "ledger.";
@endphp

<style media="screen">
#ledger_menu{
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
                <h2>Account Statment / Ledger</h2>
                <span class="jarviswidget-loader" style="display: none;"><i class="fa fa-refresh fa-spin"></i></span>
             </header>
             <div role="content" style="display: block;">
                <div class="jarviswidget-editbox">
                </div>
                <div class="widget-body no-padding">
                   <form method="post" id="dataForm2" class="smart-form" autocomplete="off" enctype="multipart/form-data" action="{{route($route_prefix.'show')}}">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
                     <input type="hidden" name="id" value="{{!empty($r->id) ? $r->id : 0}}">
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
                     <fieldset class="container">
                       <div class="row">
                         <section class="col col-md-12">
                           <div class="row">
                             <section class="col col-2">
                               From Date: <small style="color:red;">*</small>
                               <label class="input">
                                 <input type="text" class="datepicker" autocomplete="off" name="from_date" value="{{!empty(old('from_date')) ? old('from_date') : date('d.m.2021')}}" placeholder="">
                               </label>
                             </section>
                             <section class="col col-2">
                               To Date: <small style="color:red;">*</small>
                               <label class="input">
                                 <input type="text" class="datepicker" autocomplete="off" name="to_date" value="{{!empty(old('to_date')) ? old('to_date') : date('d.m.Y')}}" placeholder="">
                               </label>
                             </section>
                             <section class="col col-5">
                                <label for="name" class="label">Account Name:</label>
                                <select class="select2" name="l3_id" onchange="fetch_acc_detail(this)">
                                  <option value="">Select Account</option>
                                  @if(!empty($level3))
                                    @foreach($level3 as $acc)
                                      <option myacc_num="{{$acc->acc_num}}" @if($acc->id == old('l3_id')) {{'selected'}} @endif myacc_type="{{$acc->account_type->name}}" value="{{$acc->id}}">
                                        {{$acc->acc_name}} | {{$acc->acc_num}} | {{ $acc->account_type->name}}
                                      </option>
                                    @endforeach
                                  @endif
                                </select>
                             </section>
                             <section class="col col-2">
                               <button type="submit" id="save_btn" class="btn btn-success" style="padding:8px 16px; margin-top:15px;">
                                 Show Ledger
                               </button>
                             </section>
                           </div>
                         </section>
                       </div>
                     </fieldset>
                 </form>
                 <table id="datatable_fixed_column3" class="display table table-striped table-bordered" width="100%">
                    <thead>
                       <tr>
                          <th>Trn</th>
                          <th>Date</th>
                          <th>Type</th>
                          <th>Clear</th>
                          <th>Description</th>
                          <th>Debit</th>
                          <th>Credit</th>
                          <th>Balance</th>
                          <th>Ok</th>
                       </tr>
                    </thead>
                    <tbody>
                       @if(!empty($ledger))
                          @foreach($ledger as $l)
                            @if($loop->index > 0)
                              @php
                              $balance = $balance + (int)$l->dr - (int)$l->cr;
                              @endphp
                            @endif
                            <tr>
                              <td style="width:1%;">{{$l->id}}</td>
                              <td style="width:1%;">@if($loop->index > 0){{date('d.m.Y', strtotime($l->date))}}@endif</td>
                              <td style="width:1%;">{{$l->vt_name}}</td>
                              <td style="width:1%;">@if($loop->index > 0)<input type="checkbox" {{$l->clear ? 'checked' : ''}} onclick="clear_payment({{$l->id}}, this)" value="1" />@endif</td>
                              <td>{{$l->narration}}</td>
                              <td style="width:1%;">{{$l->dr}}</td>
                              <td style="width:1%;">{{$l->cr}}</td>
                              <td style="width:1%;">{{$balance < 0 ? '('. abs($balance). ')' : $balance}}</td>
                              <td style="width:10%;">@if($loop->index > 0)<input type="checkbox" {{$l->ok ? 'checked' : ''}} onclick="ok_payment({{$l->id}}, this)" value="1" />
                                  <a href="{{route('transaction.edit')}}/{{$l->id}}" class="btn btn-xs btn-info"> <i class="fa fa-edit"></i> </a>@endif
                              </td>
                            </tr>
                          @endforeach
                       @endif
                    </tbody>
                 </table>
                </div>
             </div>
          </div>
       </article>
    </div>
 </section>

@endsection


<script type="text/javascript">
function fetch_acc_detail(acc)
{
  $("#acc_selected_num").val($('option:selected', $(acc)).attr('myacc_num'));
  $("#acc_selected_type").val($('option:selected', $(acc)).attr('myacc_type'));
}

function clear_payment(id, elm)
{
  let text = "Are you sure to do this?";
  var vlu = $(elm).prop('checked') ? 1 : 0;
  if (confirm(text) == true) {
      $.ajax({
        url: "{{route('payment.clear')}}",
        method: 'post',
        data: {id: id, vlu:vlu},
        success: function(feedback)
        {

        }
      })
  } else
  {
    $(elm).prop('checked', false);
    return;
  }
}

function ok_payment(id, elm)
{
  let text = "Are you sure to do this?";
  var vlu = $(elm).prop('checked') ? 1 : 0;
  if (confirm(text) == true) {
      $.ajax({
        url: "{{route('payment.ok')}}",
        method: 'post',
        data: {id: id, vlu:vlu},
        success: function(feedback)
        {

        }
      })
  } else
  {
    $(elm).prop('checked', false);
    return;
  }
}
</script>
