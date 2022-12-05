<?php

namespace App\Http\Controllers;

use App\Models\Level3;
use App\Models\Level2;
use App\Models\Level1;

use App\Models\Voucher;
use App\Models\Headtype;
use App\Models\Vchtype;
use App\Models\Voucherdetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function index()
    {
        $voucher_id = Voucher::max('vch_num')+1;
        $vchs = Voucher::all();
        $htype = Headtype::all();
        $vchtype = Vchtype::all();
        $acc_d = Level3::all();

        $total_receivings = Voucherdetail::sum('cr');
        $total_payments = Voucherdetail::sum('dr');

        if(!empty(old('gl_id')))
        {
          $total_receivings = Voucherdetail::where('gl_id', old('gl_id'))->sum('cr');
          $total_payments = Voucherdetail::where('gl_id', old('gl_id'))->sum('dr');
        }

        $total_balance = $total_payments - $total_receivings;
        $total_balance = $total_balance < 0 ? "($total_balance)" : $total_balance;


        $list = Voucherdetail::where('gl_id', old('gl_id'))->where('dr', '>', 0)->orderBy('created_at')->get();
        $pays = Voucherdetail::where('gl_id', old('gl_id'))->where('cr', '>', 0)->orderBy('created_at')->get();

        return view('transaction', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expensetypes.create');
    }
    public function change_voucher_number(Request $request)
    {
      session()->put('_old_input', $request->all());
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
        if(!empty($request->id))
        {
          $success = "Voucher Updated Succssfully..!";
        }
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
              'dr' => 0,
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
              'cr' => 0,
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
       // session()->put('_old_input', $keep_selected);
       return redirect()->back()->with('success', $success);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $expensetype
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $vchs = Voucher::all();
      $vchtype = Vchtype::all();
      $acc_d = Level3::all();
      $d = Voucherdetail::where('id', $id)->first();

       $sum_dr = voucherdetail::where('level3_id', $d->level3_id)->sum('dr');
       $sum_cr = voucherdetail::where('level3_id', $d->level3_id)->sum('cr');
       $acc_balance = $sum_dr - $sum_cr;
       $acc_color = $acc_balance < 0 ? 'red' : 'green';
       $acc_balance = $acc_balance < 0 ? '('.abs($acc_balance).')' : $acc_balance;
        $total_receivings = Voucherdetail::where('gl_id', $d->gl_id)->sum('cr');
        $total_payments = Voucherdetail::where('gl_id', $d->gl_id)->sum('dr');

        $total_balance = $total_payments - $total_receivings;
        $total_balance = $total_balance < 0 ? "($total_balance)" : $total_balance;

      $list = Voucherdetail::where('gl_id', $d->gl_id)->where('dr', '>', 0)->orderBy('created_at')->get();
      $pays = Voucherdetail::where('gl_id', $d->gl_id)->where('cr', '>', 0)->orderBy('created_at')->get();

       $d->amount = $d->dr > 0 ? $d->dr : $d->cr;
       // dd($d);

        return view('transaction',get_defined_vars());
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
