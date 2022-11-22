<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Dojo;
use App\Models\Student;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public static function user_student($id)
    {
      return Student::where('user_id', $id)->pluck('id')->first();
    }
    public static function user_dojo($id)
    {
      return Dojo::where('user_id', $id)->pluck('id')->first();
    }
    public static function dojo_filetype($id)
    {
      return Dojo::where('user_id', $id)->pluck('filetype')->first();
    }
}
