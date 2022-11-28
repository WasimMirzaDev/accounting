<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Level3;
use App\Models\Level2;
use App\Models\Level1;

class LedgerTotalController extends Controller
{
    //
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
    public function ledgertotal()
    {
        $list = Level3::all();
        $level2 = Level2::all();
      return view('ledgertotal',compact(['list', 'level2']));
    }
    public function show_ledgertotal(Request $request)
    {
   
      return view('ledgertotal');
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
