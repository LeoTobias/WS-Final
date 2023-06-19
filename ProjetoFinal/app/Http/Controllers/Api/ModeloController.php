<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\StoreModeloRequest;
use App\Http\Resources\ModeloResource;
use App\Models\Modelo;
use Illuminate\Support\Str;



class ModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Monta a query com e sem paginação.
        $query = Modelo::with('fabricante');
        $mensagem = "Lista de modelos retornada";
        $codigoderetorno = 0;
        /*
        * Realiza o processamento do filtro   
        */

        // Obtem o parametro do filtro
        $filterParameter = $request -> input("filtro");

        if($filterParameter == null) {
            // Retorna todos os modelos & Default
            $mensagem = "Lista de modelos retornada - Completa";
            $codigoderetorno = 200;
        }
        else {
            // Obtem o nome do filtro e o criterio
            [$filterCriteria, $filterValue] = explode(":", $filterParameter);

            // Se o filtro está adequado
            if($filterCriteria == "nome_do_fabricante") {
                // Faz inner join para obter a categoria
                $modelos = $query->join("fabricantes", "pkfabricante", "=", "fabricante_id")->where("nome","=",$filterValue);
                $mensagem = "Lista de modelos retornada - Filtrada";
                $codigoderetorno = 200;
            }
            else {
                //Usuario chamou um filtro que não existe, então não há nada a retornar (Error 406 - Not Accepted)
                $modelos = [];
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
                        case("nome_do_modelo"):
                            $query->orderBy('modelo', $sortDirection);
                            break;
                        case("ano"):
                            $query->orderBy('ano', $sortDirection);
                            break;
                    }
                }
                $mensagem = $mensagem . "+Ordenada";
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
            $modelos = $query->get();

            $recordsTotal = Modelo::count();
            $numberOfPages = ceil($recordsTotal / $perPage);
            $response = response()->json([
                'modelos' => ModeloResource::collection($modelos),
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
            $modelos = $query->get();
            $response = response() -> json ([
                'status' => 200,
                'mensagem' => $mensagem,
                'modelos' => ModeloResource::collection($modelos)
            ],200);
        }
        else {
            // Retorna o erro que ocorreu
            $response = response() -> json ([
                'status' => 406,
                'mensagem' => $mensagem,
                'modelos' => $modelos
            ],406); 
        }

        return $response;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModeloRequest $request)
    {
        $modelo = new Modelo();

        $modelo->modelo = $request->nome_do_modelo;
        $modelo->ano = $request->ano_do_modelo;
        $modelo->fabricante_id = $request->fabricante_id;        
        $modelo->save();
        
        // Retorna o resultado
        return response() -> json([
            'status' => 200,
            'mensagem' => 'Modelo armazenado',
            'modelo' => new ModeloResource($modelo)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Modelo $modelo)
    {
        $modelo = Modelo::with('fabricante')->find($modelo->pkmodelo);

        return response() -> json([
            'status' => 200,
            'mensagem' => 'Modelo retornado',
            'produto' => new ModeloResource($modelo)
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StoreModeloRequest $request, Modelo $modelo)
    {
        $modelo = Modelo::find($modelo->pkmodelo);

        $modelo->modelo = $request->nome_do_modelo;
        $modelo->ano = $request->ano_do_modelo;
        $modelo->fabricante_id = $request->fabricante_id;        
        $modelo->update();
        
        // Retorna o resultado
        return response() -> json([
            'status' => 200,
            'mensagem' => 'Modelo atualizado',
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modelo $modelo)
    {
        $modelo->delete();

        return response() -> json([
            'status' => 200,
            'mensagem' => 'modelo apagado'
        ], 200);
    }
}
