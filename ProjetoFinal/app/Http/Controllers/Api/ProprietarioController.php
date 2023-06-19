<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreProprietarioRequest;
use App\Http\Resources\ProprietarioResource;
use App\Models\Proprietario;
use Illuminate\Support\Str;


class ProprietarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Monta a query com e sem paginação.
        $query = Proprietario::with('modelo');
        $mensagem = "Lista de proprietarios retornada";
        $codigoderetorno = 0;
        /*
        * Realiza o processamento do filtro   
        */

        // Obtem o parametro do filtro
        $filterParameter = $request -> input("filtro");

        if($filterParameter == null) {
            // Retorna todos os proprietarios & Default
            $mensagem = "Lista de proprietarios retornada - Completa";
            $codigoderetorno = 200;
        }
        else {
            // Obtem o nome do filtro e o criterio
            [$filterCriteria, $filterValue] = explode(":", $filterParameter);

            // Se o filtro está adequado
            if($filterCriteria == "nome_do_proprietario") {
                // Faz inner join para obter a categoria
                $proprietarios = $query->join("modelos", "pkmodelo", "=", "modelo_id")->where("modelo","=",$filterValue);
                $mensagem = "Lista de proprietarios retornada - Filtrada";
                $codigoderetorno = 200;
            }
            else {
                //Usuario chamou um filtro que não existe, então não há nada a retornar (Error 406 - Not Accepted)
                $proprietarios = [];
                $mensagem = "Filtro não aceito";
                $codigoderetorno = 406;
            }
        }

        if($codigoderetorno == 200) {
            /**
             * Realiza o processamento da ordenacao
             */
            // Se há input para ordenacao
            if($request->input('ordenacao', '')) {
                $sorts = explode(',', $request->input('ordenacao', ''));
                foreach($sorts as $sortColumn){
                    $sortDirection = Str::startsWith($sortColumn, '-') ? 'desc':'asc';
                    $sortColumn = ltrim($sortColumn, '-');

                    //Transforma os nomes dos parametros em nomes dos campos do Modelo
                    switch($sortColumn) {
                        case("nome_do_proprietario"):
                            $query->orderBy('nome', $sortDirection);
                            break;
                    }
                }
                $mensagem = $mensagem . "+ Ordenada";
            }
        }

        /**
         * Realiza o processamento da paginação
         */
        $input = $request->input('pagina');
        if($input) {
            $page = $input;
            $perPage = 3; // Registros por pagina
            $query->offset(($page-1) * $perPage)->limit($perPage);
            $proprietarios = $query->get();

            $recordsTotal = Proprietario::count();
            $numberOfPages = ceil($recordsTotal / $perPage);
            $response = response()->json([
                'proprietarios' => ProprietarioResource::collection($proprietarios),
                'meta' => [
                    'pagina_atual' => $page,
                    'numero_de_registros_por_pagina' => (string) $perPage,
                    'numero_total_de_registros' => (string) $recordsTotal,
                    'numero_de_paginas' => (string) $numberOfPages
                ]
            ],200);

            $mensagem = $mensagem . "+ Paginada";
        }

        // Se o processamento foi o, retornada com base no critério.
        if($codigoderetorno == 200) {
            $proprietarios = $query->get();
            $response = response() -> json ([
                'status' => 200,
                'mensagem' => $mensagem,
                'proprietarios' => ProprietarioResource::collection($proprietarios)
            ],200);
        }
        else {
            // Retorna o erro que ocorreu
            $response = response() -> json ([
                'status' => 406,
                'mensagem' => $mensagem,
                'proprietarios' => $proprietarios
            ],406); 
        }

        return $response;

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProprietarioRequest $request)
    {
        $proprietario = new Proprietario();

        $proprietario->nome = $request->nome_do_proprietario;
        $proprietario->modelo_id = $request->modelo_id;        
        $proprietario->save();
        
        // Retorna o resultado
        return response() -> json([
            'status' => 200,
            'mensagem' => 'Proprietario armazenado',
            'proprietario' => new ProprietarioResource($proprietario)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Proprietario $proprietario)
    {
        $proprietario = Proprietario::with('modelo')->find($proprietario->pkproprietario);

        return response() -> json([
            'status' => 200,
            'mensagem' => 'Proprietario retornado',
            'proprietario' => new ProprietarioResource($proprietario)
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProprietarioRequest $request, Proprietario $proprietario)
    {
        $proprietario->nome = $request->nome_do_proprietario;
        $proprietario->modelo_id = $request->modelo_id;   
        
        $proprietario->update();
        
        // Retorna o resultado
        return response() -> json([
            'status' => 200,
            'mensagem' => 'Proprietario atualizado',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proprietario $proprietario)
    {
        $proprietario->delete();

        return response() -> json([
            'status' => 200,
            'mensagem' => 'modelo apagado'
        ], 200);
    }
}
