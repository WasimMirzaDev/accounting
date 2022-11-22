<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dojo;
use App\Models\Student;
use App\Models\Rank;
use App\Models\Program;
use App\Models\Attendance;
use App\Models\Note;
use Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
class StudentController extends Controller
{
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
     $dojos = Dojo::all();
     $ranks = Rank::all();
     $programs = Program::all();
     if(auth()->user()->role == 2)
     {
       $dojo_id = $this->user_dojo(auth()->user()->id);
       $dojos = [];
       // $ranks = Rank::where('dojo_id', $dojo_id)->get();
       // $programs = Program::where('dojo_id', $dojo_id)->get();
       $list = Student::where('dojo_id', $dojo_id)->get();
     }
     else
     {
       $list = Student::all();
     }
       $students = [];
       return view('students',get_defined_vars());
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
     $success = "Student Created Succssfully..!";

     if(empty($request->dojo_id) && auth()->user()->role == 2)
     {
       $request->merge([
         'dojo_id' => $this->user_dojo(Auth::id()),
       ]);
     }


     if($request->id > 0)
     {
       $student = Student::whereId($request->id)->first();
       $user_id = $student->user->id;
       $user_password = $student->user->password;
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
       $success = "Student Updated Succssfully..!";
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
  // 'email' => 'required|email|unique:users,email,'. $user_id,
  'dojo_id' => 'required',
  'email' => 'required',
  'firstname' => 'required',
  'lastname' => 'required',
  'birthdate' => 'required',
  'login' => 'required|unique:users,login,'. $user_id,
  'password' => 'required'
]);
     if(!$validator->passes())
     {
       return redirect()->back()->withInput()->with(['error_came' => true, 'success' => 0, 'errors'=>$validator->errors()->all()]);
     }
       $request->active = empty($request->active) ? 0 : 1;

       $u =  User::updateOrCreate(['id' => $user_id],
          [
            'name' => $request->firstname.' '.$request->lastname,
            'role' => 3,
            'login' => $request->login,
            'email' => $request->email,
            'password' => $request->password,
            'active' => $request->active
          ]
       );

    $d =  Student::updateOrCreate(['id' => $request->id],
       [
         'user_id' => $u->id,
         'dojo_id' => $request->dojo_id,
         'firstname' => $request->firstname,
         'lastname' => $request->lastname,
         'rank_id' =>  $request->rank_id,
         'program_id' =>  $request->program_id,
         'picture' =>  "",
         'salutation' =>  $request->salutation,
         'sex' =>  $request->sex,
         'address' =>  $request->address,
         'city' =>  $request->city,
         'country' =>  $request->country,
         'province' =>  $request->province,
         'postal' =>  $request->postal,
         'homephone' =>  $request->homephone,
         'workphone' =>  $request->workphone,
         'mobilephone' =>  $request->mobilephone,
         'birthdate' =>  date('Y-m-d', strtotime($request->birthdate)),
         'emergcontact1' =>  $request->emergcontact1,
         'emergcontact2' =>  $request->emergcontact2,
         'lastexam' =>  date('Y-m-d', strtotime($request->lastexam)),
         'startdate' =>  date('Y-m-d', strtotime($request->startdate)),
         'expiry' =>  date('Y-m-d', strtotime($request->expiry)),
         'tuitionprice' =>  $request->tuitionprice,
         'proglength' =>  0,
         'notes' =>  "",
         'stripes' => $request->stripes
       ]
   );
   if($d)
   {
     $entity_id = $d->id;
     if($request->hasFile("file"))
     {
       $fileName = $entity_id.".".$request->file->extension();
       $filetype = $request->file->extension();
       $request->file->move(public_path('uploads/students/'), $fileName);
       $updateable = Student::find($entity_id);
       $updateable->filetype = '.'.$filetype;
       $updateable->update();
     }
   }

       return redirect()->route('students.show')
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
     $r = Student::where('id', $id)->first();
     $ranks = Rank::all();
     $programs = Program::all();
     $dojos = Dojo::all();
     if(auth()->user()->role == 2)
     {
        $dojo_id = $this->user_dojo(auth()->user()->id);
        $dojos = [];
        $r = Student::where('id', $id)->where('dojo_id', $dojo_id)->first();
    //   $ranks = Rank::where('dojo_id', $dojo_id)->get();
    //   $programs = Program::where('dojo_id', $dojo_id)->get();
     }
      // dd($d);
      $student_log = Attendance::where('login_id', $r->user->login)->get();
	  $student_note = Note::where('student_id', $id)->orderBy('created_at','desc')->get();
       return view('students',get_defined_vars());
   }
   
   public function edit_students(Request $request){
       
	   if(!isset($request->ids)){
		return 0;
	   }
	   $array = [];
		if(isset($request->stripe)){
			$array['stripes'] = $request->stripe;
		}
		if(isset($request->rank_id) && $request->rank_id){
			$array['rank_id'] = $request->stripe;
		}
		if(isset($request->program_id) && $request->program_id){
			$array['program_id'] = $request->program_id;
		}
		if(isset($request->start_date) && $request->start_date){
			$array['startdate'] = date("Y-m-d",strtotime($request->start_date));
		}
		if(isset($request->expiry) && $request->expiry){
			$array['expiry'] = date("Y-m-d",strtotime($request->expiry));
		}
		if(isset($request->pop_lastexam) && $request->pop_lastexam){
			$array['lastexam'] = date("Y-m-d",strtotime($request->pop_lastexam));
		}
		foreach($request->ids as $id){
			Student::where('id',$id)->update($array);
			if(isset($request->pop_active)){
				$user = Student::where('id',$id)->first();
				User::where('id',$user->user_id)->update([
					'active'=>$request->pop_active
				]);
			}
		}
		return 1;
   }
   
   public function add_note(Request $request){
	   if($request->note){
		   if(isset($request->id)){
		   Note::create([
		   'note'=>$request->note,
		   'student_id'=>$request->id]);
		   }else{
			   foreach($request->ids as $id){
				    Note::create([
		   'note'=>$request->note,
		   'student_id'=>$id]);
			   }
		   }
	   return 1;
	   }
	   return 0;
   }

   public function copy($id)
   {
     $r = Student::where('id', $id)->first();
     $ranks = Rank::all();
     $programs = Program::all();
     $dojos = Dojo::all();
     if(auth()->user()->role == 2)
     {
        $dojo_id = $this->user_dojo(auth()->user()->id);
        $dojos = [];
       $r = Student::where('id', $id)->where('dojo_id', $dojo_id)->first();
       $ranks = Rank::where('dojo_id', $dojo_id)->get();
       $programs = Program::where('dojo_id', $dojo_id)->get();
     }

     // $r->merge([
     //   'id' => 0,
     // ]);
     $r->id = "";
     $r->copy = 1;
     $r->firstname = "";
     $r->sex = 'M';
     $r->birthdate = "";
     $r->user->login = "";
       return view('students',get_defined_vars());
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
        $deleted = Student::find($id)->delete();
        if($deleted)
        {
          return response()->json(['success'=>1, 'msg'=>'Deleted Successfully!']);
        }
        return response()->json(['success'=>0, 'msg'=>'Error in deleting record!']);
    }
}
