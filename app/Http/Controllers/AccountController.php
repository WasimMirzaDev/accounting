<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\Level2;
use App\Models\Level3;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $list = Level3::all();
       $level2 = Level2::all();
       return view('account',compact(['list', 'level2']));
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
     $success = "Account Created Succssfully..!";

     $request->validate([
       'acc_num' => 'required|unique:acs_level3,acc_num,' . $request->id,
       'acc_name' => 'required',
       'level2_id' => 'required'
     ],
     [
        'acc_num.required' => 'Account Number is required',
        'acc_num.unique' => $request->acc_num. ' account number has already been taken',
        'acc_name.required' => 'Account Name is required',
        'level2_id.required' => 'Account Type is required'
    ]);

       $request->active = empty($request->active) ? 0 : 1;
       $request->opening_dr = empty($request->opening_dr) ? 0 : $request->opening_dr;
       $request->opening_cr = empty($request->opening_cr) ? 0 : $request->opening_cr;
       
    $d =  Level3::updateOrCreate(['id' => $request->id],
       [
         'acc_num' => $request->acc_num,
         'acc_name' => $request->acc_name,
         'level2_id' => $request->level2_id,
         'city_code' => $request->city_code,
         'care_of' => $request->care_of,
         'mobile1' => $request->mobile1,
         'mobile2' => $request->mobile2,
         'ptcl1' => $request->ptcl1,
         'ptcl2' => $request->ptcl2,
         'map_address' => $request->map_address,
         'address' => $request->address,
         'opening_dr' => $request->opening_dr,
         'opening_cr' => $request->opening_cr,
         'active' => $request->active
       ]
   );

   if($d)
   {
     $account_id = $d->id;
     if($request->hasFile("file"))
     {
       $fileName = $account_id.".".$request->file->extension();
       $profile_pic = $request->file->extension();
       $request->file->move(public_path('uploads/accounts/'), $fileName);
       $updateable = Level3::find($account_id);
       $updateable->profile_pic = '.'.$profile_pic;
       $updateable->update();
     }
   }

       return redirect()->route('account.show')
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
     $level2 = Level2::all();
      $d = Level3::where('id', $id)->first();
      // dd($d);
       return view('account',get_defined_vars());
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
