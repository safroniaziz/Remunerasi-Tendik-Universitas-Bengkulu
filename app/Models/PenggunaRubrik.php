<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenggunaRubrik extends Model
{
    use HasFactory;
    protected $fillable = [
        'rubrik_id',
        'unit_id',
        
    ];
    public function periode()
    {
        return $this->belongsTo('App\Models\PeriodeInsentif');
    }
}
