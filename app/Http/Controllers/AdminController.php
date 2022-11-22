<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;
class AdminController extends Controller
{
    public function profile()
    {
      $u = User::whereId(auth()->user()->id)->first();
      return view('profile', get_defined_vars());
    }
    public function update_profile(Request $request)
    {
      $user_id = auth()->user()->id;
      $user_password = User::whereId($user_id)->pluck('password')->first();
      if(empty($request->password))
      {
        $request->validate([
          'name' => 'required',
          'login' => 'required|unique:users,login,'. $user_id
        ]);
        $u =  User::whereId($user_id)->update(
           [
             'name' => $request->name,
             'login' => $request->login,
             'email' => $request->email
           ]
        );
      }
      else
      {
        $request->validate([
          'name' => 'required',
          'login' => 'required|unique:users,login,'. $user_id,
          'password' => 'required|confirmed'
        ]);
        $u =  User::whereId($user_id)->update(
           [
             'name' => $request->name,
             'login' => $request->login,
             'email' => $request->email,
             'password' => Hash::make($request->password)
           ]
        );
      }


  if($u)
  {
    $entity_id = $user_id;
    if($request->hasFile("file"))
    {
      $fileName = $entity_id.".".$request->file->extension();
      $filetype = $request->file->extension();
      $request->file->move(public_path('uploads/admin/'), $fileName);
      $updateable = User::find($entity_id);
      $updateable->filetype = '.'.$filetype;
      $updateable->update();
    }
  }

      return redirect()->route('admin.profile')
                      ->with('success',"Profile Update Successfully");
    }
}
