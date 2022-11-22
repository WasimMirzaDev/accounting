<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucherdetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'a_gld';
    public function account_name(){
        return $this->belongsTo(Level3::class, 'level3_id', 'id');
    }
    public function voucher_type(){
        return $this->belongsTo(Vchtype::class, 'vt_id', 'id');
    }
}
