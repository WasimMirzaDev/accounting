<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
  public function index(){
    return view('upload-attendance');
  }

  public function daily(){
    return view('daily-attendance');
  }

  public function attendance_summary(){
    return view('attendance-summary');
  }

  public function attendance_summary_report(Request $request){
    $datef = date('Y-m-d', strtotime($request->datef));
    $datet = date('Y-m-d', strtotime($request->datet));
    $auth_user = auth()->user()->id;
if(auth()->user()->role == 2)
{
  $dojo_id = $this->user_dojo(auth()->user()->id);
      $list = DB::SELECT("SELECT distinct firstname, lastname, dojo_name, login,
    (select count(*) from attendance_vu as va where va.at_status = 'P' and va.st_id = v.st_id) as presents,
    (select count(*) from attendance_vu as va where va.at_status = 'A' and va.st_id = v.st_id) as absents,
    ag.grade
    FROM attendance_vu as v
    left outer join attendance_grades as ag on (select count(*) from attendance_vu as va where va.at_status = 'A' and va.st_id = v.st_id) between ag.from_value and ag.to_value and ag.user_id = $auth_user
    where v.at_date between '$datef' and '$datet' and v.dojo_id = $dojo_id order by dojo_name, login
    ");
}

if(auth()->user()->role == 1)
{
  $list = DB::SELECT("SELECT distinct firstname, lastname, dojo_name, login,
              (select count(*) from attendance_vu as va where va.at_status = 'P' and va.st_id = v.st_id) as presents,
              (select count(*) from attendance_vu as va where va.at_status = 'A' and va.st_id = v.st_id) as absents,
              ag.grade
              FROM attendance_vu as v
              left outer join attendance_grades as ag on (select count(*) from attendance_vu as va where va.at_status = 'A' and va.st_id = v.st_id) between ag.from_value and ag.to_value and ag.user_id = $auth_user
              where v.at_date between '$datef' and '$datet' order by dojo_name, login
              ");
}
    return view('attendance-summary', get_defined_vars());
  }

  public function daily_report(Request $request){
    $att_date = date('Y-m-d', strtotime($request->att_date));
    if(auth()->user()->role == 2)
    {
      $dojo_id = $this->user_dojo(auth()->user()->id);
      $att = DB::select("SELECT firstname, lastname, login, attime, d.name as dojo_name from attendance as a
                          inner join users as u on u.login = a.login_id
                          left outer join students as s on s.user_id = u.id
                          left outer join dojos as d on d.id = s.dojo_id
                          WHERE a.attime like '$att_date%' AND d.id = $dojo_id
                          order by a.id");
    }
    if(auth()->user()->role == 1)
    {
      $att = DB::select("SELECT firstname, lastname, login, attime, d.name as dojo_name from attendance as a
        inner join users as u on u.login = a.login_id
        left outer join students as s on s.user_id = u.id
        left outer join dojos as d on d.id = s.dojo_id
        WHERE a.attime like '$att_date%'
        order by a.id");
    }

return view('attendance-report', get_defined_vars());
  }

  public function upload_attendane($attendance)
  {
    $response['total'] = count($attendance);
    $response['uploaded'] = 0;
    $allowed_students = User::where('role', 3)->pluck('login')->toArray();
    if(auth()->user()->role == 2)
    {
      $dojo_id = $this->user_dojo(auth()->user()->id);
      $allowed_students = DB::select("select login from users as u inner join students as s on s.user_id = u.id where s.dojo_id = $dojo_id")[0]->login;
    }
    $allowed_students = (array) $allowed_students;
    foreach ($attendance as $att) {
      $login_id = $att[0];
      $timestamp = $att[1];
      $date = date('Y-m-d', strtotime($timestamp));
      $login_exist = User::where('login', $login_id)->count();


      if($login_exist == 1)
      {
        $already_exists = Attendance::where('attime', 'like', "$date%")->where('login_id', $login_id)->count();
        // if($already_exists == 0 && in_array($login_id, $allowed_students))
        if($already_exists == 0)
        {
          $a = new Attendance;
          $a->login_id = $login_id;
          $a->attime = $timestamp;
          if($a->save())
          {
            $response['uploaded'] += 1;
          }
        }
      }
    }
    return $response;
  }

  public function store(Request $request){
    $validator = Validator::make($request->all(), [
      'file' => 'required|mimes:csv,txt|max:10000',
    ]);

    $fname = mt_rand(1, time());
    if ($validator->passes()) {
      if($request->hasFile("file"))
      {
        $fileName = $fname.".".$request->file->extension();
        $request->file->move(storage_path('uploads/attendance/'), $fileName);
        $attendance = [];
                if (($open = fopen(storage_path() . "/uploads/attendance/".$fileName, "r")) !== FALSE) {
                    while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                        $attendance[] = $data;
                    }
                    fclose($open);
                };
          $uploaded = $this->upload_attendane($attendance);
        return response()->json(['success'=>1, 'msg'=> $uploaded['uploaded'].'/'.$uploaded['total'].' Uploaded Successfully!', 'data' => $uploaded]);
      }
    }
    return response()->json(['success' => 0, 'msg'=>$validator->errors()->all()]);
  }
  
  public function student_attendance()
    {
        return view('student-attendance');
    }
    public function save_student_attendance(Request $request)
    {
        $message = ['class' => 'alert alert-danger', 'msg'=>'Error !!', 'student' => '', 'success' => 0];
        
        $student_id = $request->student_id;
        $user = User::where('login', $student_id)->pluck('name')->first();
        $message['student'] = $user;
        if(empty($user))
        {
            $message['msg'] = 'ID Not Recognized';
        }
        else 
        {
            $attendance = Attendance::where('login_id', '=', $student_id)->where('attime', 'like', date('Y-m-d').'%')->pluck('id')->first();
            if(!empty($attendance))
            {
                $message['msg'] = 'Attendance already marked !!';
            }
            else 
            {
                $Att = new Attendance;
                $Att->login_id = $student_id;
                $Att->attime = date('Y-m-d H:i:s');
                if($Att->save())
                {
                    $message = ['class' => 'alert alert-success', 'msg'=>'Attendance marked successfully !!', 'success' => 1, 'student' => $user];
                }
            }
        }
        return json_encode($message);
    }
}
