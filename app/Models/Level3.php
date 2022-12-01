<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level3 extends Model
{
    use HasFactory;
    protected $table = 'acs_level3';
    protected $guarded = ['id'];
    public function account_type(){
        return $this->belongsTo(Level2::class, 'level2_id', 'id');
    }

    public function all_vouchers(){
        return $this->hasMany(Voucherdetail::class, 'level3_id', 'id');
    }
}
