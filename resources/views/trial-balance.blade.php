@extends('layouts.app')

@section('content')
@php
$route_prefix = "trial.";
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
                <h2>Trial Balance</h2>
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
                                 <input type="text" class="datepicker" autocomplete="off" name="from_date" value="{{!empty(old('from_date')) ? old('from_date') : date('2021-m-d')}}" placeholder="">
                               </label>
                             </section>
                             <section class="col col-2">
                               To Date: <small style="color:red;">*</small>
                               <label class="input">
                                 <input type="text" class="datepicker" autocomplete="off" name="to_date" value="{{!empty(old('to_date')) ? old('to_date') : date('Y-m-d')}}" placeholder="">
                               </label>
                             </section>
                             <section class="col col-3">
                                <label for="name" class="label" style="margin-bottom: 0px;">Level:</label>
                                <select class="select2" name="l1_id" onchange="fetch_acc_detail(this)">
                                  <option value="">Select Level</option>
                                  @if(!empty($list))
                                    @foreach($list as $acc)
                                      <option myacc_num="{{$acc->acc_num}}" @if($acc->id == old('l1_id')) {{'selected'}} @endif myacc_type="{{$acc->name}}" value="{{$acc->id}}">
                                        {{$acc->name}}
                                      </option>
                                    @endforeach
                                  @endif
                                </select>
                             </section>
                             <section class="col col-3">
                                <label for="name" class="label" style="margin-bottom: 0px;">Subtype:</label>
                                <select class="select2" name="subtype" onchange="fetch_acc_detail(this)">
                                  <option value="">Choose Type</option>

                                  @if(!empty($level2))
                                    @foreach($level2 as $acc)
                                      <option myacc_num="{{$acc->name}}" @if($acc->id == old('subtype')) {{'selected'}} @endif myacc_type="{{$acc->name}}" value="{{$acc->id}}">
                                      {{$acc->name}}
                                      </option>
                                    @endforeach
                                  @endif
                                </select>
                             </section>
                             <section class="col col-2">
                               <button type="submit" id="save_btn" class="btn btn-success" style="padding:8px 16px; margin-top:15px;">
                                 Show Trial Balance
                               </button>
                             </section>
                           </div>
                           <!-- <div class="row">
                             <section class="col col-2">
                               Account #:
                               <label class="input">
                                 <input type="text" class="readonly" id="acc_selected_num" autocomplete="off" name="accnum" value="{{old('accnum')}}" placeholder="" readonly>
                               </label>
                             </section>
                             <section class="col col-3">
                              Type:
                               <label class="input">
                                 <input type="text" class="readonly" id="acc_selected_type" autocomplete="off" name="acctype" value="{{old('acctype')}}" placeholder="" readonly>
                               </label>
                             </section>
                             <section class="col col-2">
                               <button type="submit" id="save_btn" class="btn btn-success" style="padding:8px 16px; margin-top:15px;">
                                 Show Ledger
                               </button>
                             </section>
                           </div> -->
                         </section>
                       </div>
                     </fieldset>
                 </form>
                 <table width="100%" style="margin-bottom: 10px !important;">
                    <tr>
                        <th width="25%" style="padding:20px; background-color:#007BFF; color:white;text-align:center">Total Opening Balance :{{$total_opening < 0 ? '('.abs($total_opening).')' : $total_opening}}</th>
                        
                        <th width="25%" style="padding:20px; background-color:#28A745; color:white;text-align:center">Total Debit:{{$total_drs < 0 ? '('.abs($total_drs).')' : $total_drs}}</th>
                        
                        <th width="25%" style="padding:20px; background-color:#343A40; color:white;text-align:center">Total Credit:{{$total_cr < 0 ? '('.abs($total_cr).')' : $total_cr}}</th>
                        
                        <th width="25%" style="padding:20px; background-color:#6C757D; color:white;text-align:center">Total Closing Balance :{{$total_closing < 0 ? '('.abs($total_closing).')' : $total_closing}}</th>
                        
                    </tr>
                </table>
                <hr>
                 <table id="datatable_fixed_column3" class="display table table-striped table-bordered" width="100%">
                    <thead>
                       <!-- <tr>
                          <th class="hasinput">
                             <input type="text" class="form-control" placeholder="" />
                          </th>
                          <th class="hasinput">
                             <input class="form-control" placeholder="" type="text">
                          </th>
                          <th></th>
                          <th></th>
                          <th></th>
                          
                          <th></th>
                       </tr> -->
                       <tr>
                          <th>Acc#</th>
                          <th>Title</th>
                          <th>Opening Balance</th>
                          <th>Total Debit</th>
                          <th>No.Dr. Trans</th>
                          <th>Total Credit</th>
                          <th>No.Cr. Trans</th>
                          <th>Closing Balance</th>
                       </tr>
                    </thead>
                    <tbody>
                       @if(!empty($trialbalance))
                          @foreach($trialbalance as $tb) 
                            <tr>
                              <td>{{$tb->acc_num}}</td>
                              <td>{{$tb->acc_name}}</td>
                              <td>{{$tb->total_opening_balance < 0 ? '('.abs($tb->total_opening_balance).')' : $tb->total_opening_balance}}</td>
                              <td>{{$tb->dr < 0 ? '('.abs($tb->dr).')' : $tb->dr}}</td> 
                              <td>{{$tb->total_dr_transactions < 0 ? '('.abs($tb->total_dr_transactions).')' : $tb->total_dr_transactions}}</td>
                              <td>{{$tb->cr < 0 ? '('.abs($tb->cr).')' : $tb->cr}}</td>
                              <td>{{$tb->total_cr_transactions < 0 ? '('.abs($tb->total_cr_transactions).')' : $tb->total_cr_transactions}}</td>
                              <td>{{$tb->total_closing_balance < 0 ? '('.abs($tb->total_closing_balance).')' : $tb->total_closing_balance}}</td>
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
</script>
