<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level2 extends Model
{
    use HasFactory;
    protected $table = 'acs_level2';
    protected $guarded = ['id'];
    public function account_head(){
        return $this->belongsTo(Level1::class, 'level1_id', 'id');
    }
}
