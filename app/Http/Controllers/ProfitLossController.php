<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Level3;
use App\Models\Level2;
use App\Models\Level1;
use Illuminate\Support\Facades\DB;

class ProfitLossController extends Controller
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
        return view('account',compact(['list', 'level2']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profitloss()
    {
        $list = Level3::all();
        $level2 = Level2::all();
        $total_exp = 0;
        $total_inc = 0;
        $profit_or_loss = '';
      return view('profitloss', get_defined_vars());
    }
    public function show_profitloss(Request $request)
    {
      $request->validate([
        // 'acc_num' => 'required|unique:acs_level3,acc_num,' . $request->id,
        'p_from' => 'required',
        'p_to' => 'required'
      ],
      [
         'p_from.required' => 'From Date is required',
         'p_to.required' => 'To Date is required'
     ]);
     $p_from = date('Y-m-d', strtotime($request->p_from));
     $p_to = date('Y-m-d', strtotime($request->p_to));
      session()->put('_old_input', $request->all());
      $list = Level3::all();
      $level2 = Level2::all();

      $expenses = DB::SELECT(
        "SELECT l3.acc_num, l3.acc_name, gd.level3_id, ifnull(sum(cr), 0) as amt FROM `a_gld` as gd
        inner join acs_level3 as l3 on l3.id = gd.level3_id
        where gd.level3_id in (SELECT id from acs_level3 where level2_id in (select id from acs_level2 where level1_id = 4)) and gd.doc_date between '$p_from' and '$p_to'
        group by l3.acc_num, l3.acc_name, gd.level3_id"
      );

      $income = DB::SELECT(
        "SELECT l3.acc_num as acc_num1, l3.acc_name as acc_name1, gd.level3_id as level3_id1, ifnull(sum(dr), 0) as amt1 FROM `a_gld` as gd
        inner join acs_level3 as l3 on l3.id = gd.level3_id
        where gd.level3_id in (SELECT id from acs_level3 where level2_id in (select id from acs_level2 where level1_id = 3)) and gd.doc_date between '$p_from' and '$p_to'
        group by l3.acc_num, l3.acc_name, gd.level3_id"
      );




      if(count($expenses) >= count($income))
      {
        foreach($expenses as $key => $exp)
        {
          if(!empty($income[$key]))
          {
            $expenses[$key]->acc_num1 = $income[$key]->acc_num1;
            $expenses[$key]->acc_name1 = $income[$key]->acc_name1;
            $expenses[$key]->level3_id1 = $income[$key]->level3_id1;
            $expenses[$key]->amt1 = $income[$key]->amt1;
          }
          else
          {
            $expenses[$key]->acc_num1 = "";
            $expenses[$key]->acc_name1 = "";
            $expenses[$key]->level3_id1 = "";
            $expenses[$key]->amt1 = "";
          }
        }
        $fetch_table = $expenses;
      }


      if(count($income) > count($expenses))
      {
        foreach($income as $key => $inc)
        {
          if(!empty($expenses[$key]))
          {
            $income[$key]->acc_num = $expenses[$key]->acc_num;
            $income[$key]->acc_name = $expenses[$key]->acc_name;
            $income[$key]->level3_id = $expenses[$key]->level3_id;
            $income[$key]->amt = $expenses[$key]->amt;
          }
          else
          {
            $income[$key]->acc_num = "";
            $income[$key]->acc_name = "";
            $income[$key]->level3_id = "";
            $income[$key]->amt = "";
          }
        }
        $fetch_table = $income;
      }


      $total_income = DB::SELECT(
        "SELECT sum(dr) as total_inc from a_gld where level3_id in (SELECT id from acs_level3 where level2_id
        in (select id from acs_level2 where level1_id = 3)) and doc_date between '$p_from' and '$p_to'"
      );

      $total_expense = DB::SELECT(
        "SELECT sum(cr) as total_exp from a_gld where level3_id in (SELECT id from acs_level3 where level2_id
        in (select id from acs_level2 where level1_id = 4)) and doc_date between '$p_from' and '$p_to'"
      );
      $total_exp = $total_expense[0]->total_exp;
      $total_inc = $total_income[0]->total_inc;
      $profit_or_loss = $total_exp > $total_inc ? 'Loss' : 'Profit';
      return view('profitloss', get_defined_vars());
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
