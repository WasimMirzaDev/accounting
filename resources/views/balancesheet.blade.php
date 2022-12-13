@extends('layouts.app')

@section('content')
@php
$route_prefix = "balancesheet.";
@endphp

<style media="screen">
#ledger_menu{
  color:white !important;
}

label, .col {
  color:black;
}
#datatable_fixed_column td{
  padding:2px !important;
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
                <h2>Balance Sheet</h2>
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
                             <section class="col col-3">
                                <label for="name" class="label" style="margin-bottom: 0px;">Formated Values with Barkets</label>
                                <select class="select2" name="formated">
                                  <option @if(old('formated') == 1) selected @endif value="1">Yes</option>
                                  <option @if(old('formated') == '0') selected @endif value="0">No</option>
                                </select>
                             </section>
                             <section class="col col-2">
                               Upto Date: <small style="color:red;">*</small>
                               <label class="input">
                                 <input type="text" class="datepicker" autocomplete="off" name="upto_date" value="{{!empty(old('upto_date')) ? old('upto_date') : date('d.m.Y')}}" placeholder="">
                               </label>
                             </section>
                             <section class="col col-2">
                               <button type="submit" id="save_btn" class="btn btn-success" style="width:150px;padding:8px 16px; margin-top:15px;">
                                 Show Balance Sheet
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
                        <th width="50%" style="padding:10px; background-color:#DC3545; text-align:center;">Liabilities</th>
                        <th width="50%" style="padding:10px; background-color:#28A76B; text-align:center;">Assets</th>
                    </tr>
                </table>
                <div class="row">
                  <div class="col col-md-12">
                    <table data-order=[] id="datatable_fixed_column" class="display table table-striped "  width="100%">
                       <thead>
                          <tr>
                            <th style="background-color:lightgrey" width="10%">Acc#</th>
                            <th style="background-color:lightgrey" width="30%">Title</th>
                            <th style="background-color:lightgrey" width="10%">Amount</th>
                             <th style="background-color:lightgrey" width="10%">Acc#</th>
                             <th style="background-color:lightgrey" width="30%">Title</th>
                             <th style="background-color:lightgrey" width="10%">Amount</th>
                          </tr>
                       </thead>
                       <tbody>
                          @if(!empty($fetch_table))
                             @foreach($fetch_table as $exp)
                               <tr style="height:20px;">
                                 @php $bg = ''; @endphp
                                 @if($exp->type == 'head')
                                  @php $bg = '#338BAB'; @endphp
                                 @endif
                                 @if($exp->type == 'total')
                                  @php $bg = 'lightgrey'; @endphp
                                 @endif


                                 @php $bg1 = ''; @endphp
                                 @if($exp->type1 == 'head')
                                  @php $bg1 = '#338BAB'; @endphp
                                 @endif
                                 @if($exp->type1 == 'total')
                                  @php $bg1 = 'lightgrey'; @endphp
                                 @endif

                                  @if(empty($formated))

                                    <td style="background-color:{{$bg}}">{{$exp->acc_num}}</td>
                                    <td style="background-color:{{$bg}}">{{$exp->acc_name}}</td>
                                    <td style="background-color:{{$bg}}">{{!empty($exp->amt) ? number_format((int)$exp->amt) : ''}}</td>
                                    <td style="background-color:{{$bg1}}">{{$exp->acc_num1}}</td>
                                    <td style="background-color:{{$bg1}}">{{$exp->acc_name1}}</td>
                                    <td style="background-color:{{$bg1}}">{{!empty($exp->amt1) ? number_format((int)$exp->amt1) : ''}}</td>
                                    @else

                                    <td style="background-color:{{$bg}}">{{$exp->acc_num}}</td>
                                    <td style="background-color:{{$bg}}">{{$exp->acc_name}}</td>
                                    <td style="background-color:{{$bg}}">
                                      @if(!empty($exp->amt))
                                        @if($exp->amt > 0)
                                          {{number_format((int)$exp->amt)}}
                                        @else
                                        ({{number_format(abs((int)$exp->amt))}})
                                        @endif
                                      @endif
                                    </td>
                                    <td style="background-color:{{$bg1}}">{{$exp->acc_num1}}</td>
                                    <td style="background-color:{{$bg1}}">{{$exp->acc_name1}}</td>
                                    <td style="background-color:{{$bg1}}">
                                      @if(!empty($exp->amt1))
                                        @if($exp->amt1 > 0)
                                          {{number_format((int)$exp->amt1)}}
                                        @else
                                        ({{number_format(abs((int)$exp->amt1))}})
                                        @endif
                                      @endif
                                    </td>

                                  @endif

                               </tr>
                             @endforeach
                             <tr style="background-color:#FFC107;">
                               <td></td>
                               <td>Total Liabilities:
                               @if(!empty($formated))
                                 @if(!empty($total_liab))
                                   @if($total_liab > 0)
                                     {{number_format((int)$total_liab)}}
                                   @else
                                   ({{number_format(abs((int)$total_liab))}})
                                   @endif
                                 @endif
                               @else
                                {{$total_liab}}
                               @endif

                               </td>
                               <td></td>
                               <td></td>
                               <td>Total Assets:
                                @if(!empty($formated))
                                 @if(!empty($total_assets))
                                   @if($total_assets > 0)
                                     {{number_format((int)$total_assets)}}
                                   @else
                                   ({{number_format(abs((int)$total_assets))}})
                                   @endif
                                 @endif
                               @else
                                {{$total_assets}}
                               @endif
                               </td>
                               <td></td>
                             </tr>
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


<script type="text/javascript">
function fetch_acc_detail(acc)
{
  $("#acc_selected_num").val($('option:selected', $(acc)).attr('myacc_num'));
  $("#acc_selected_type").val($('option:selected', $(acc)).attr('myacc_type'));
}
</script>
