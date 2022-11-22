<?php

namespace App\Http\Controllers;
use App\Models\Level1;
use App\Models\Level2;
use Illuminate\Http\Request;

class AccountTypeController extends Controller
{
    public function index()
    {
        $list = Level2::all();
        $level1 = Level1::all();

        return view('account-type',get_defined_vars());
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
        'level1_id' => 'required'
     ],
     [
        'name.required' => 'Account Type name is required',
        'level1_id.required' => 'Account Head is required'
    ]);
    Level2::updateOrCreate(['id' => $request->id],
     [
       'name' => $request->name,
        'level1_id' => $request->level1_id
     ]);
     return redirect()->route('account-type.show')
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
      $r = Level2::where('id', $id)->first();
      $level1 = Level1::all();
       return view('account-type',get_defined_vars());
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
        $deleted = Level2::find($id)->delete();
        if($deleted)
        {
          return response()->json(['success'=>1, 'msg'=>'Deleted Successfully!']);
        }
        return response()->json(['success'=>0, 'msg'=>'Error in deleting record!']);
    }
}
