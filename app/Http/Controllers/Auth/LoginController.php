<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


// custom
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo()
    {
      if(auth()->user()->role == 1)
      {
        return route('admin.dashboard');
      }
      else if(auth()->user()->role == 2)
      {
        return route('dojo.dashboard');
      }
      else if(auth()->user()->role == 3)
      {
        return route('student.dashboard');
      }
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
      $input = $request->all();
       $this->validate($request,[
           'login'=>'required',
           'password'=>'required'
       ]);

       if( auth()->attempt(array('login'=>$input['login'], 'password'=>$input['password'], 'active' => 1)) ){
         return redirect()->route('home');
       }
       else{
           return redirect()->route('login')->with('login','Incorrect credentials..!');
       }
    }
}
