<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Headtype;
use App\Models\Vchtype;
use App\Models\Level3;
use App\Models\Voucherdetail;
use Illuminate\Support\Facades\DB;
class VoucherController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function index()
 {
   $voucher_id = Voucher::max('vch_num')+1;
   $vchs = Voucher::all();
   $htype = Headtype::all();
   $vchtype = Vchtype::all();
   $acc_d = Level3::all();
   $list = Voucherdetail::where('gl_id', old('gl_id'))->where('dr', '>', 0)->orderBy('created_at')->get();
   $pays = Voucherdetail::where('gl_id', old('gl_id'))->where('cr', '>', 0)->orderBy('created_at')->get();
   // dd($list);
     return view('voucher', get_defined_vars());
 }

 public function get_ledger()
 {
   $level3 = Level3::all();
   return view('ledger', get_defined_vars());
 }

 public function change_voucher_number(Request $request)
 {
   session()->put('_old_input', $request->all());
 }

 /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
 public function create()
 {

 }

 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
 public function store(Request $request)
 {
   $success = "Voucher Created Succssfully..!";
   $request->validate([
     // 'acc_num' => 'required|unique:acs_level3,acc_num,' . $request->id,
     'gl_id' => 'required',
     'vt_id' => 'required',
     'doc_num' => 'required',
     'doc_date' => 'required',
     'amount' => 'required',
     'level3_id' => 'required',
   ],
   [
      'gl_id.required' => 'Voucher Number is required',
      // 'acc_num.unique' => $request->acc_num. ' account number has already been taken',
      'vt_id.required' => 'Voucher Type is required',
      'level3_id.required' => 'Account Name is required',
      'doc_num.required' => 'Doc Number is required',
      'doc_date.required' => 'Doc Date is required',
      'amount.required' => 'Amount is required'
  ]);
  $vtype = Vchtype::where('id', $request->vt_id)->pluck('is_receiving')->first();

  $request->doc_date = date('Y-m-d', strtotime($request->doc_date));
  if($vtype == 1)
  {
    $d =  Voucherdetail::updateOrCreate(['id' => $request->id],
       [
         'gl_id' => $request->gl_id,
         'vt_id' => $request->vt_id,
         'level3_id' => $request->level3_id,
         'doc_num' => $request->doc_num,
         'doc_date' => $request->doc_date,
         'cr' => $request->amount,
         'description' => $request->description,
         'narration' => $request->narration
       ]
   );
  }
  else
  {
    $d =  Voucherdetail::updateOrCreate(['id' => $request->id],
       [
         'gl_id' => $request->gl_id,
         'vt_id' => $request->vt_id,
         'level3_id' => $request->level3_id,
         'doc_num' => $request->doc_num,
         'doc_date' => $request->doc_date,
         'dr' => $request->amount,
         'description' => $request->description,
         'narration' => $request->narration
       ]
   );
  }
  $keep_selected = [
    'gl_id' => $request->gl_id,
    'vch_selected_date' => $request->vch_selected_date,
    'vch_selected_num' => $request->vch_selected_num,
    'head_selected_type' => $request->head_selected_type
  ];
  session()->put('_old_input', $keep_selected);

  return redirect()->back()->with('success', $success);
     // return redirect()->route('voucher.show')
     //                 ->with('success',$success);
 }




 public function store_headtype(Request $request)
 {
   $success = "Account Created Succssfully..!";

   $request->validate([
     'vch_num' => 'required|unique:a_gl,vch_num,' . $request->id,
     'gl_date' => 'required',
     'ht_id' => 'required',
   ],
   [
      'vch_num.required' => 'Voucher Number is required',
      'vch_num.unique' => $request->vch_num. ' voucher number has already been taken',
      'ht_id.required' => 'Account Head Type is required',
      'gl_date.required' => 'Voucher Date is required'
  ]);
  $request->merge(['gl_date' => date('Y-m-d', strtotime($request->gl_date))]);
  $d =  Voucher::updateOrCreate(['id' => $request->id], $request->all());
  $keep_selected = [
    'gl_id' => $d->id,
    'vch_selected_date' => $request->gl_date,
    'vch_selected_num' => $d->vch_num,
    'head_selected_type' => $request->ht_id
  ];
  session()->put('_old_input', $keep_selected);
     return redirect()->route('voucher.show')
                     ->with('success',$success);
 }

 /**
  * Display the specified resource.
  *
  * @param  \App\Product  $expensetype
  * @return \Illuminate\Http\Response
  */
 public function show(Product $expensetype)
 {
     return view('expensetypes.show',compact('expensetype'));
 }

 public function show_ledger(Request $request)
 {

   // $level3_id = $request->level3_id;


   // $tenant_id = $request->tenant_id;
   // $tenant = Tenant::find($request->tenant_id);
   // $from_date = date('Y-m-d', strtotime($request->from_date));
   // $to_date = date('Y-m-d', strtotime($request->to_date));
   // $prev_
   $level3 = Level3::all();
   $level3_id = $request->l3_id;
   $from_date = date('Y-m-d', strtotime($request->from_date));
   $to_date = date('Y-m-d', strtotime($request->to_date));

   $ledger = DB::SELECT("
   SELECT '' as created_at, '' as ord, '' as id, '' as date, 'Opening' as narration, (CASE WHEN opening > 0 THEN opening ELSE '' END)  as dr, (CASE WHEN opening < 0 THEN ABS(opening) ELSE '' END) as cr
   FROM (
     select sum(opening) as opening from (
     SELECT sum(dr) - sum(cr) as opening from a_gld WHERE level3_id = $level3_id and doc_date < '$from_date'
     UNION ALL
     SELECT opening_dr-opening_cr AS opening FROM acs_level3 where id=$level3_id
     ) as fopening

   ) AS opening_balance


   UNION ALL

   SELECT gld.created_at, 1 as ord, gld.id, gld.doc_date,  gld.narration, dr, cr
   FROM a_gld AS gld WHERE dr > 0 AND level3_id = $level3_id AND gld.doc_date BETWEEN '$from_date' and '$to_date'

   UNION ALL

   SELECT gld.created_at, 1 as ord, gld.id, gld.doc_date,  gld.narration, dr, cr
   FROM a_gld AS gld WHERE cr > 0 AND level3_id = $level3_id AND gld.doc_date BETWEEN '$from_date' and '$to_date'
   ORDER BY 4,2
   ");

   $balance = 0;
   if(!empty($ledger[0]->dr))
   {
     $balance = $ledger[0]->dr;
   }
   else if(!empty($ledger[0]->cr))
   {
     $balance = 0-$ledger[0]->cr;
   }
   session()->put('_old_input', $request->all());
   return view('ledger', get_defined_vars());
 }




 /**
  * Show the form for editing the specified resource.
  *
  * @param  \App\Product  $expensetype
  * @return \Illuminate\Http\Response
  */
 public function edit($id)
 {
   $level2 = Level2::all();
    $d = Level3::where('id', $id)->first();
    // dd($d);
     return view('account',get_defined_vars());
 }

 /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \App\Product  $product
  * @return \Illuminate\Http\Response
  */
 public function update(Request $request, Product $product)
 {
     $request->validate([
         'name' => 'required',
         'detail' => 'required',
     ]);

     $product->update($request->all());

     return redirect()->route('products.index')
                     ->with('success','Expense type updated successfully');
 }

 /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Product  $product
  * @return \Illuminate\Http\Response
  */

  public function destroy($id)
  {
      $deleted = Level3::find($id)->delete();
      if($deleted)
      {
        return response()->json(['success'=>1, 'msg'=>'Deleted Successfully!']);
      }
      return response()->json(['success'=>0, 'msg'=>'Error in deleting record!']);
  }


}
