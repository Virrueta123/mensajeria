<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lote extends Model
{
    use HasFactory;
    protected $table = "lote"; 
    public $timestamps = true;
    protected $primaryKey = 'lote_id';
}
