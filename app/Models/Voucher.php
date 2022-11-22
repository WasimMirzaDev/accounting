<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $table = "a_gl";
    protected $guarded = ['id'];
    public function head_type(){
        return $this->belongsTo(Headtype::class, 'ht_id', 'id');
    }
}
