<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModeloResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id'                        => $this->pkmodelo,
            'nome_do_modelo'            => $this->modelo,
            'ano_do_modelo'             => $this->ano,
            'fabricante'                => new FabricanteResource($this->fabricante)
        ];
    }
}
