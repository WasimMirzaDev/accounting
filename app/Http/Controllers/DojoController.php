<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dojo;
use Illuminate\Support\Facades\Hash;
use Validator;
class DojoController extends Controller
{
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $list = Dojo::all();
       $dojos = [];
       return view('dojos',compact(['list', 'dojos']));
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

     $user_id = 0;
     $success = "Dojo Created Succssfully..!";
     if($request->id > 0)
     {
       $dojo = Dojo::whereId($request->id)->first();
       $user_id = $dojo->user->id;
       $user_password = $dojo->user->password;
       if(empty($request->password))
       {
         $request->merge([
           'password' => $user_password,
         ]);
       }
       else
       {
         $request->merge([
           'password' => Hash::make($request->password),
         ]);
       }
       $success = "Dojo Updated Succssfully..!";
     }
     else
     {
       if(!empty($request->password))
       {
         $request->merge([
           'password' => Hash::make($request->password),
         ]);
       }
     }
$validator = Validator::make($request->all(), [
  'name' => 'required',
  'email' => 'required',
  // 'email' => 'required|users,email,'. $user_id,
  'phone' => 'required|unique:dojos,phone,'. $request->id,
  'login' => 'required|unique:users,login,'. $user_id,
  // 'identifier' => 'required|unique:dojos,identifier,'. $request->id,
  'password' => 'required'
]);
     if(!$validator->passes())
     {
       return redirect()->back()->withInput()->with(['error_came' => true, 'success' => 0, 'errors'=>$validator->errors()->all()]);
     }
       $request->active = empty($request->active) ? 0 : 1;

       $u =  User::updateOrCreate(['id' => $user_id],
          [
            'name' => $request->name,
            'role' => 2,
            'login' => $request->login,
            'email' => $request->email,
            'password' => $request->password,
            'active' => $request->active
          ]
       );

    $d =  Dojo::updateOrCreate(['id' => $request->id],
       [
         'user_id' => $u->id,
         'name' => $request->name,
         'owner' => $request->owner,
         'phone' => $request->phone,
         'email' => $request->email,
         'identifier' => $request->identifier,
         'country' => $request->country,
         'province' => $request->province,
         'city' => $request->city,
         'postal' => $request->postal,
         'address' => $request->address,
       ]
   );

   if($d)
   {
     $entity_id = $d->id;
     if($request->hasFile("file"))
     {
       $fileName = $entity_id.".".$request->file->extension();
       $filetype = $request->file->extension();
       $request->file->move(public_path('uploads/dojos/'), $fileName);
       $updateable = Dojo::find($entity_id);
       $updateable->filetype = '.'.$filetype;
       $updateable->update();
     }
   }

       return redirect()->route('dojos.show')
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
      $d = Dojo::where('id', $id)->first();
      // dd($d);
       return view('dojos',get_defined_vars());
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
        $deleted = Dojo::find($id)->delete();
        if($deleted)
        {
          return response()->json(['success'=>1, 'msg'=>'Deleted Successfully!']);
        }
        return response()->json(['success'=>0, 'msg'=>'Error in deleting record!']);
    }
}
