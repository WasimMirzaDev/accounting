<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function dojo(){
        return $this->belongsTo(Dojo::class);
    }
    public function program(){
        return $this->belongsTo(Program::class);
    }
    public function rank(){
        return $this->belongsTo(Rank::class);
    }
}
