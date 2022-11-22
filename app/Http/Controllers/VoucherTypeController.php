<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vchtype;
class VoucherTypeController extends Controller
{
  public function index()
  {
      $list = Vchtype::all();
      return view('vouchertype',get_defined_vars());
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
      'name' => 'required',
      'short_name' => 'required',
      'is_receiving' => 'required'
   ],
   [
      'name.required' => 'Type name is required',
      'short_name.required' => 'Type short name is required',
      'is_receiving.required' => 'Type is required'

  ]);
  VchType::updateOrCreate(['id' => $request->id],
   [
     'name' => $request->name,
     'short_name' => $request->short_name,
     'is_receiving' => $request->is_receiving
   ]);
   return redirect()->route('vouchertype.show')
                   ->with('success','Account Type created successfully.');
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
    $r = VchType::where('id', $id)->first();
     return view('vouchertype',get_defined_vars());
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
      $deleted = Vchtype::find($id)->delete();
      if($deleted)
      {
        return response()->json(['success'=>1, 'msg'=>'Deleted Successfully!']);
      }
      return response()->json(['success'=>0, 'msg'=>'Error in deleting record!']);
  }
}
