<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class alumno_lote extends Model
{
    use HasFactory;
    protected $table = "alumno_lote"; 
    public $timestamps = true;
    protected $primaryKey = 'fm_id';
}
