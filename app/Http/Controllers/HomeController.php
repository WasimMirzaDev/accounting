<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Dojo;
use App\Models\Student;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    //   $role = auth()->user()->role;
    //   $dojo_id = $this->user_dojo(auth()->user()->id);
    //   $total_programs   = Program::count();
    //   if(auth()->user()->role == 1)
    //   {
    //     $total_students     = Student::count();
    //     $total_programs   = Program::count();
    //   }
    //   else
    //   if(auth()->user()->role == 2)
    //   {
    //     $total_students     = Student::where('dojo_id', $dojo_id)->count();
    //     // $total_programs   = Program::where('dojo_id', $dojo_id)->count();
    //   }
    //   $total_dojos = Dojo::count();


        return view('home', get_defined_vars());
    }
}
