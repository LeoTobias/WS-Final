<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fabricante extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'pais'];

    protected $primaryKey = 'pkfabricante';

    protected $table = 'fabricantes';

    public $incrementing = true;

    public $timestamps = false;

}
