<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceGrade;
class GradingPolicyController extends Controller
{
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $list = AttendanceGrade::where('user_id', auth()->user()->id)->get();
       return view('grading-policy',compact('list'));
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

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
     $request->validate([
         'grade' => 'required',
         'from_value' => 'required',
         'to_value' => 'required'
     ]);

    AttendanceGrade::updateOrCreate(['id' => $request->id],
     [
       'grade' => $request->grade,
       'from_value' => $request->from_value,
       'to_value' => $request->to_value,
       'user_id' => auth()->user()->id
     ]);
     return redirect()->route('grading-policy.show')
                     ->with('success','Policy created successfully.');

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
      $r = AttendanceGrade::where('id', $id)->first();
       return view('grading-policy',get_defined_vars());
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
        $deleted = AttendanceGrade::find($id)->delete();
        if($deleted)
        {
          return response()->json(['success'=>1, 'msg'=>'Deleted Successfully!']);
        }
        return response()->json(['success'=>0, 'msg'=>'Error in deleting record!']);
    }
}
