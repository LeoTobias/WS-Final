<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Modelo;


class Proprietario extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    protected $primaryKey = 'pkproprietario';

    protected $table = 'proprietarios';

    public $incrementing = true;

    public $timestamps = false;

    public function modelo() {
        return $this->belongsTo(Modelo::class, 'modelo_id','pkmodelo');
    }
}
