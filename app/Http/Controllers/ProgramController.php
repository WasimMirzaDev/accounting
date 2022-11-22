<?php

namespace App\Http\Controllers;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $list = Program::all();
       return view('programs',compact('list'));
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
     ]);
    Program::updateOrCreate(['id' => $request->id],
     [
       'name' => $request->name,
       'dojo_id' => $request->dojo_id,
       'proglength' => $request->proglength ?? 0,
       'tuitionprice' => $request->tuitionprice ?? 0
     ]);
     return redirect()->route('programs.show')
                     ->with('success','Program created successfully.');
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
      $r = Program::where('id', $id)->first();
       return view('programs',get_defined_vars());
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
        $deleted = Program::find($id)->delete();
        if($deleted)
        {
          return response()->json(['success'=>1, 'msg'=>'Deleted Successfully!']);
        }
        return response()->json(['success'=>0, 'msg'=>'Error in deleting record!']);
    }
}
