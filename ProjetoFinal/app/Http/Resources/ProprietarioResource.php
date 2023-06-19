<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProprietarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        // return parent::toArray($request);

        return [
            'id'                              => $this->pkproprietario,
            'nome_do_proprietario'            => $this->nome,
            'modelo'                          => new ModeloResource($this->modelo)
        ];

    }
}
