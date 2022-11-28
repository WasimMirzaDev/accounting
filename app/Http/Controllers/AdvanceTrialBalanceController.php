<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Level3;
use App\Models\Level2;
use App\Models\Level1;

use Illuminate\Support\Facades\DB;

class AdvanceTrialBalanceController extends Controller
{
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $list = Level1::all();
        $level2 = Level2::all();
        return view('account', get_defined_vars());
    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function advancetrial()
    {
        $list = Level1::all();
        $level2 = Level2::all();
        $total_drs = 0;
        $total_cr = 0;
        $total_opening = 0;
        $total_closing = 0;
      return view('advancetrial', get_defined_vars());
    }
    public function show_advancetrial (Request $request)
    {
      $level3 = Level3::all();
      $level2 = Level2::all();
      $list = Level1::all();
      $level1_id = $request->l1_id;
      $level2_id = $request->subtype;
      $from_date = date('Y-m-d', strtotime($request->from_date));
      $to_date = date('Y-m-d', strtotime($request->to_date));

      $l1_where = $l2_where = "";
      if(!empty($level2_id))
      {
          $l2_where = " AND l2.id = $level2_id";
      }

      if(!empty($level1_id))
      {
          $l1_where = " AND l1.id = $level1_id";
      } 
       

      $total_dr_balance = DB::SELECT(
          " select ifnull(SUM(dr), 0) as total_dr from a_gld as igd where igd.dr > 0 and doc_date between '$from_date' and '$to_date'"
      );
      $total_drs = $total_dr_balance[0]->total_dr;

      $total_cr_balance = DB::SELECT(
          " select ifnull(SUM(cr), 0) as total_cr from a_gld as igd where igd.cr > 0 and doc_date between '$from_date' and '$to_date'"
      );
      $total_cr = $total_cr_balance[0]->total_cr;

      $opening = DB::SELECT("select ifnull(sum(dr) - sum(cr), 0) as total_opening from a_gld where doc_date < '$from_date' and level3_id in (select level3_id from a_gld where doc_date between '$from_date' and '$to_date') ");
      $total_opening = $opening[0]->total_opening;

      $closing = DB::SELECT("select ifnull(sum(dr) - sum(cr), 0) as total_closing from a_gld where doc_date <= '$to_date' and level3_id in (select level3_id from a_gld where doc_date between '$from_date' and '$to_date')  ");
      $total_closing = $closing[0]->total_closing;


     $trialbalance = DB::SELECT("
      select l3.acc_num, gd.level3_id, l3.acc_name, ifnull(sum(dr), 0) as dr, ifnull(sum(cr), 0) as cr,
          ifnull((select count(*) from a_gld as igd where igd.level3_id = gd.level3_id and igd.dr > 0), 0) as total_dr_transactions,
          ifnull((select count(*) from a_gld as igd where igd.level3_id = gd.level3_id and igd.cr > 0), 0) as total_cr_transactions,
          ifnull((select sum(dr) - sum(cr) from a_gld where level3_id = gd.level3_id and doc_date < '$from_date'), 0) as total_opening_balance,
          ifnull((select sum(dr) - sum(cr) from a_gld where level3_id = gd.level3_id and doc_date <= '$to_date'), 0) as total_closing_balance,
          ifnull((select cr as widthdrawal from a_gld where cr > 0 and level3_id = gd.level3_id order by created_at desc limit 1), 0) as widthdrawal,
          ifnull((select dr as deposit from a_gld where dr > 0 and level3_id = gd.level3_id order by created_at desc limit 1), 0) as deposit
          from a_gld as gd
          inner join acs_level3 as l3 on l3.id = gd.level3_id
          inner join acs_level2 as l2 on l2.id = l3.level2_id
          inner join acs_level1 as l1 on l1.id = l2.level1_id
          WHERE gd.doc_date between '$from_date' and '$to_date' $l1_where $l2_where
          group by l3.acc_num, gd.level3_id, l3.acc_name
          ORDER BY l1.id, l2.id, l3.id
      ");
      session()->put('_old_input', $request->all());
      return view('advancetrial',get_defined_vars());
    }
    public function create()
    {
        return view('expensetypes.create');
    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
 
    }
 
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $expensetype
     * @return \Illuminate\Http\Response
     */
    public function show()
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
      
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
 
     public function destroy($id)
     {
     }
}
