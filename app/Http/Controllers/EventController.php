<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Stat;
use App\Models\Student;
use App\Models\Dojo;
use App\Models\EventDetail;
use Illuminate\Support\Facades\DB;
use Response;
use Validator;
class EventController extends Controller
{

public function add(Request $request)
{

  $status = Stat::all();


  if(auth()->user()->role == 2)
  {
    $dojo_id = $this->user_dojo(auth()->user()->id);
    $list = Student::where('dojo_id', $dojo_id)->get();
  }
  if(auth()->user()->role == 1)
  {
    $list = Student::all();
  }

  $dojos = [];
  if(auth()->user()->role == 1)
  {
    $dojos = Dojo::all();
  }

  if(empty($request->id))
  {
    $stat_id = 0;
    $title = $note = "";
    $event_students = [];
    extract($request->all());
    // dd(date('Y-m-d', strtotime($start)));
    return response()->json([
      'statusCode' => 200,
      'html' => view('event-detail', get_defined_vars())->render(),
    ]);
  }
  else
  {
    $ed = Event::whereId($request->id)->first();
    $id = $ed->id;
    $title = $ed->title;
    $note = $ed->note;
    $start = $ed->start;
    $end = $ed->end;
    $stat_id = $ed->stat_id;
    $dojo_id = $ed->dojo_id;
    $allDay = $ed->allDay;
    $event_students = EventDetail::where('event_id', $request->id)->pluck('student_id')->toArray();
    return response()->json([
      'statusCode' => 200,
      'html' => view('event-detail', get_defined_vars())->render(),
    ]);
  }
}

  public function index()
  {
    $dojos = [];
    if(auth()->user()->role ==1)
    {
      $dojos = Dojo::all();
    }
      if(request()->ajax())
      {
       $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
       $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
       if(auth()->user()->role == 2)
       {
         $dojo_id = $this->user_dojo(auth()->user()->id);
         $data = DB::SELECT("SELECT e.id, e.title, allDay,
         (CASE WHEN allDay = true THEN date(start) ELSE DATE_FORMAT(e.start, '%Y-%m-%d %H:%i') END) AS start,
         (CASE WHEN allDay = true THEN date(e.end) ELSE DATE_FORMAT(e.end, '%Y-%m-%d %H:%i') END) AS end, color, editable
         FROM events
         as e
         inner join stats as s on s.id = e.stat_id
         WHERE dojo_id = $dojo_id
         ");
       }
       if(auth()->user()->role == 1)
       {
         if(!empty($_GET['dojo_id']))
         {
           $dojo_id = $_GET['dojo_id'];
           $data = DB::SELECT("SELECT e.id, e.title, allDay,
           (CASE WHEN allDay = true THEN date(start) ELSE DATE_FORMAT(e.start, '%Y-%m-%d %H:%i') END) AS start,
           (CASE WHEN allDay = true THEN date(e.end) ELSE DATE_FORMAT(e.end, '%Y-%m-%d %H:%i') END) AS end, color, editable
           FROM events
           as e
           inner join stats as s on s.id = e.stat_id
           WHERE dojo_id = $dojo_id
           ");
         }
         else
         {
           $data = DB::SELECT("SELECT e.id,  e.title, allDay,
           (CASE WHEN allDay = true THEN date(start) ELSE DATE_FORMAT(e.start, '%Y-%m-%d %H:%i') END) AS start,
           (CASE WHEN allDay = true THEN date(e.end) ELSE DATE_FORMAT(e.end, '%Y-%m-%d %H:%i') END) AS end, color, editable
           FROM events
           as e
           inner join stats as s on s.id = e.stat_id
           ");
         }
       }
       return response()->json($data, 200, [], JSON_NUMERIC_CHECK);
    //   return json_encode($data);
      }
      return view('events', get_defined_vars());
  }


  public function create(Request $request)
  {
    $event_id   = $request->id;
    $student_id = !empty($request->student_id) ? $request->student_id : array();

    if(auth()->user()->role == 2)
    {
       $dojo_id = $this->user_dojo(auth()->user()->id);
       $request->merge(['dojo' => $dojo_id]);
    }


    $validator = Validator::make($request->all(), [
      'title' => 'required',
    //   'number' => 'required|unique:units,number,' . $request->id,
      'start' => 'required',
      'end' => 'required',
      'stat_id' => 'required',
      'dojo'       => 'required'
    ]);

$allDay = empty($request->allDay) ? 'false' : 'true';

if ($validator->passes()) {
  $insertArr = [
    'title' => $request->title,
    'stat_id' => $request->stat_id,
    'note' => $request->note,
    'dojo_id' => $request->dojo,
    'start' => date('Y-m-d H:i:s', strtotime($request->start)),
    'end' => date('Y-m-d H:i:s', strtotime($request->end)),
    'starttime' => date('H:i:s', strtotime($request->start)),
    'endtime' => date('H:i:s', strtotime($request->end)),
    'allDay' => $allDay
  ];


  $event =  Event::updateOrCreate(['id' => $event_id],$insertArr);

  if($event)
  {
    $event_id = $event->id;
    EventDetail::where('event_id', $event_id)->delete();
    foreach($student_id as $st)
    {
      $ed = new EventDetail;
      $ed->student_id = $st;
      $ed->event_id   = $event_id;
      $ed->save();
    }
    $data = DB::SELECT("SELECT e.id, concat(title, '\n', time_format(starttime, '%h:%i %p') ) as title, e.title as ename, e.start, date_add(e.end, interval 1 day) as end, s.color
    FROM events
    as e
    inner join stats as s on s.id = e.stat_id
    WHERE e.id = $event_id
    ")[0];

    return response()->json(['success'=>1, 'msg'=>'Saved Successfully!', 'data' => $event]);
  }
}
      return response()->json(['success' => 0, 'msg'=>$validator->errors()->all()]);
  }


  public function update(Request $request)
  {
      $where = array('id' => $request->id);
      
      $updateArr = ['start' => date('Y-m-d H:i', strtotime($request->start)), 'end' => date('Y-m-d H:i', strtotime($request->end))];
      $event  = Event::where($where)->update($updateArr);

      return Response::json($event);
  }


  public function destroy(Request $request)
  {
      $event = Event::where('id',$request->id)->delete();
      if($event)
      {
          EventDetail::where('event_id', $request->id)->delete();
          return response()->json(['success'=>1, 'msg'=>'Deleted Successfully!', 'id'=>$request->id]);
      }
          return response()->json(['success'=>0, 'msg'=>'Error in deleting record!']);
  }

}
