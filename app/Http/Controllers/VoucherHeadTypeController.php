<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Headtype;
class VoucherHeadTypeController extends Controller
{
  public function index()
  {
      $list = Headtype::all();
      return view('voucherheadtype',get_defined_vars());
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
   $request->validate([
      'name' => 'required'
   ],
   [
      'name.required' => 'Type name is required'

  ]);
  Headtype::updateOrCreate(['id' => $request->id],
   [
     'name' => $request->name
   ]);
   return redirect()->route('voucherheadtype.show')
                   ->with('success','Head Type Created successfully.');
 }

 /**
  * Display the specified resource.
  *
  * @param  \App\Product  $expensetype
  * @return \Illuminate\Http\Response
  */
 public function show(Product $expensetype)
 {

 }

 /**
  * Show the form for editing the specified resource.
  *
  * @param  \App\Product  $expensetype
  * @return \Illuminate\Http\Response
  */
 public function edit($id)
 {
    $r = Headtype::where('id', $id)->first();
     return view('voucherheadtype',get_defined_vars());
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

 }

 /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Product  $product
  * @return \Illuminate\Http\Response
  */

  public function destroy($id)
  {
      $deleted = Headtype::find($id)->delete();
      if($deleted)
      {
        return response()->json(['success'=>1, 'msg'=>'Deleted Successfully!']);
      }
      return response()->json(['success'=>0, 'msg'=>'Error in deleting record!']);
  }
}
