@extends('layouts.app')

@section('content')
@php
$route_prefix = "profitloss.";
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
                <h2>Profit & Loss Account Report</h2>
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
                                 <input type="text" class="datepicker" autocomplete="off" name="p_from" value="{{!empty(old('p_from')) ? old('p_from') : date('d.m.2021')}}" placeholder="">
                               </label>
                             </section>
                             <section class="col col-2">
                               To Date: <small style="color:red;">*</small>
                               <label class="input">
                                 <input type="text" class="datepicker" autocomplete="off" name="p_to" value="{{!empty(old('p_to')) ? old('p_to') : date('d.m.Y')}}" placeholder="">
                               </label>
                             </section>
                             <section class="col col-2">
                               <button type="submit" id="save_btn" class="btn btn-success" style="width:130px;padding:8px 16px; margin-top:15px;">
                                 Show
                               </button>
                             </section>
                           </div>
                           <!-- <div class="row">

                             <section class="col col-2">
                               <button type="submit" id="save_btn" class="btn btn-success" style="padding:8px 16px; margin-top:15px;">
                                 Show
                               </button>
                             </section>
                           </div> -->
                         </section>
                       </div>
                     </fieldset>
                 </form>
                 <table width="100%" style="margin-bottom: 10px !important;">
                    <tr>
                        <th width="50%" style="padding:10px; background-color:#28A76B; color:white;text-align:center;">Income</th>

                        <th width="50%" style="padding:10px; background-color:#FF9AA8; color:white;text-align:center;">Expenses</th>

                    </tr>
                </table>
                <div class="row">
                  <div class="col col-md-12">
                    <table data-order=[] id="datatable_fixed_column" class="display table table-striped table-bordered"  width="100%">
                       <thead>
                          <tr>
                             <th style="background-color:#28A76B" width="10%">Acc#</th>
                             <th style="background-color:#28A76B" width="30%">Title</th>
                             <th style="background-color:#28A76B" width="10%">Amount</th>
                             <th style="background-color:#FF9AA8" width="10%">Acc#</th>
                             <th style="background-color:#FF9AA8" width="30%">Title</th>
                             <th style="background-color:#FF9AA8" width="10%">Amount</th>
                          </tr>
                       </thead>
                       <tbody>
                          @if(!empty($fetch_table))
                             @foreach($fetch_table as $exp)
                               <tr style="height:40px;">
                                 <td>{{$exp->acc_num1}}</td>
                                 <td>{{$exp->acc_name1}}</td>
                                 <td>{{!empty($exp->amt1) ? number_format((int)$exp->amt1) : ''}}</td>
                                 <td>{{$exp->acc_num}}</td>
                                 <td>{{$exp->acc_name}}</td>
                                 <td>{{!empty($exp->amt) ? number_format((int)$exp->amt) : ''}}</td>
                               </tr>
                             @endforeach
                          @endif
                          <tr style="height:40px; background:#17A2B8; font-weight:bold;font-size:18px;">
                            <td style="border-right:none;">{{$profit_or_loss}}</td>
                            <td style="border-left:none;">{{number_format(abs($total_inc - $total_exp))}}</td>
                            <td >{{number_format($total_inc)}}</td>
                            <td></td>
                            <td></td>
                            <td >{{number_format($total_exp)}}</td>
                          </tr>
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
<script type="text/javascript">
function fetch_acc_detail(acc)
{
  $("#acc_selected_num").val($('option:selected', $(acc)).attr('myacc_num'));
  $("#acc_selected_type").val($('option:selected', $(acc)).attr('myacc_type'));
}
</script>
