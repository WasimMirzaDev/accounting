<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level3;
use App\Models\Level2;
use App\Models\Level1;
use Illuminate\Support\Facades\DB;
class BalanceSheetController extends Controller
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
    public function balancesheet()
    {
        $list = Level3::all();
        $level2 = Level2::all();
      return view('balancesheet', get_defined_vars());
    }
    public function show_balancesheet (Request $request)
    {
      $upto_date = date('Y-m-d', strtotime($request->upto_date));
      $formated = $request->formated;
      $expenses = DB::SELECT("
      SELECT * from (
        SELECT DISTINCT 0 as order2, '' AS acc_num, l2.name AS acc_name, l3.level2_id, '' AS level3_id, '' AS amt, 'head' AS type from a_gld as gld
        INNER JOIN acs_level3 AS l3 on l3.id = gld.level3_id
        INNER JOIN acs_level2 AS l2 on l2.id = l3.level2_id
        WHERE gld.doc_date <= '$upto_date' AND gld.level3_id in (SELECT id FROM acs_level3 WHERE level2_id IN (SELECT id FROM acs_level2 WHERE level1_id = 2))
        GROUP BY l3.level2_id, l2.name
      UNION ALL

      SELECT 1 as order2, l3.acc_num, l3.acc_name, l3.level2_id, gd.level3_id, ifnull(sum(cr), 0) - ifnull(sum(dr), 0) as amt, '' as type FROM `a_gld` as gd
              inner join acs_level3 as l3 on l3.id = gd.level3_id
              where gd.level3_id in (SELECT id from acs_level3 where level2_id in (select id from acs_level2 where level1_id = 2))
              and gd.doc_date <= '$upto_date'
              group by l3.acc_num, l3.acc_name, gd.level3_id
      UNION ALL

      SELECT 3 as order2, '' as acc_num, concat('Total ', l2.name) as acc_name, l3.level2_id, '' as level3_id, sum(cr)-sum(dr) as amt, 'total' as type from a_gld as gld
      inner join acs_level3 as l3 on l3.id = gld.level3_id
      inner join acs_level2 as l2 on l2.id = l3.level2_id
      WHERE gld.doc_date <= '$upto_date' and gld.level3_id in (SELECT id from acs_level3 where level2_id in (select id from acs_level2 where level1_id = 2))
      group by l3.level2_id, l2.name
      ) as lb order by level2_id, order2, acc_name;
      ");

      $income = DB::SELECT("
      SELECT acc_num as acc_num1, acc_name as acc_name1, amt as amt1, type as type1 from (
                SELECT DISTINCT 0 as order2, '' AS acc_num, l2.name AS acc_name, l3.level2_id, '' AS level3_id, '' AS amt, 'head' AS type from a_gld as gld
                INNER JOIN acs_level3 AS l3 on l3.id = gld.level3_id
                INNER JOIN acs_level2 AS l2 on l2.id = l3.level2_id
                WHERE gld.doc_date <= '$upto_date' AND gld.level3_id in (SELECT id FROM acs_level3 WHERE level2_id IN (SELECT id FROM acs_level2 WHERE level1_id = 1))
                GROUP BY l3.level2_id, l2.name
              UNION ALL

              SELECT 1 as order2, l3.acc_num, l3.acc_name, l3.level2_id, gd.level3_id, ifnull(sum(dr), 0) - ifnull(sum(cr), 0) as amt, '' as type FROM `a_gld` as gd
                      inner join acs_level3 as l3 on l3.id = gd.level3_id
                      where gd.level3_id in (SELECT id from acs_level3 where level2_id in (select id from acs_level2 where level1_id = 1))
                      and gd.doc_date <= '$upto_date'
                      group by l3.acc_num, l3.acc_name, gd.level3_id
              UNION ALL

              SELECT 3 as order2, '' as acc_num, concat('Total ', l2.name) as acc_name, l3.level2_id, '' as level3_id, sum(dr)-sum(cr) as amt, 'total' as type from a_gld as gld
              inner join acs_level3 as l3 on l3.id = gld.level3_id
              inner join acs_level2 as l2 on l2.id = l3.level2_id
              WHERE gld.doc_date <= '$upto_date' and gld.level3_id in (SELECT id from acs_level3 where level2_id in (select id from acs_level2 where level1_id = 1))
              group by l3.level2_id, l2.name
              ) as lb order by level2_id, order2, acc_name;
      ");


      if(count($expenses) >= count($income))
      {
        foreach($expenses as $key => $exp)
        {
          if(!empty($income[$key]))
          {
            $expenses[$key]->acc_num1 = $income[$key]->acc_num1;
            $expenses[$key]->acc_name1 = $income[$key]->acc_name1;
            $expenses[$key]->type1 = $income[$key]->type1;
            $expenses[$key]->amt1 = $income[$key]->amt1;
          }
          else
          {
            $expenses[$key]->acc_num1 = "";
            $expenses[$key]->acc_name1 = "";
            $expenses[$key]->type1 = "";
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
            $income[$key]->type = $expenses[$key]->type;
            $income[$key]->amt = $expenses[$key]->amt;
          }
          else
          {
            $income[$key]->acc_num = "";
            $income[$key]->acc_name = "";
            $income[$key]->type = "";
            $income[$key]->amt = "";
          }
        }
        $fetch_table = $income;
      }

      $total_assets = DB::SELECT("
      SELECT ifnull(sum(dr), 0) - ifnull(sum(cr), 0) as amt FROM `a_gld` as gd
      where gd.level3_id in (SELECT id from acs_level3 where level2_id in (select id from acs_level2 where level1_id = 1))
      AND gd.doc_date <= '$upto_date';
      ")[0]->amt;


      $total_liab = DB::SELECT("
      SELECT ifnull(sum(cr), 0) - ifnull(sum(dr), 0) as amt FROM `a_gld` as gd
      where gd.level3_id in (SELECT id from acs_level3 where level2_id in (select id from acs_level2 where level1_id = 2))
      AND gd.doc_date <= '$upto_date';
      ")[0]->amt;
      session()->put('_old_input', $request->all());
      return view('balancesheet', get_defined_vars());
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
