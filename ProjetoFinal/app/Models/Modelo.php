<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Fabricante;


class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['modelo', 'ano'];

    protected $primaryKey = 'pkmodelo';

    protected $table = 'modelos';

    public $incrementing = true;

    public $timestamps = false;

    public function fabricante() {
        return $this->belongsTo(Fabricante::class, 'fabricante_id','pkfabricante');
    }

}
