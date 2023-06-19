<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FabricanteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->pkfabricante,
            'nome_do_fabricante' => $this->nome,
            'pais' => $this->pais
        ];
    }
}
