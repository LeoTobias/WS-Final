<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\StoreFabricanteRequest;
use App\Http\Resources\FabricanteResource;
use App\Models\Fabricante;

class FabricanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fabricantes = Fabricante::all();

        return response()->json([
            'status' => 200,
            'mensagem' => 'Lista de fabricantes retornada',
            'fabricantes' => FabricanteResource::collection($fabricantes)
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFabricanteRequest $request)
    {
        $fabricante = new Fabricante();
        $fabricante->nome = $request->nome_do_fabricante;
        $fabricante->pais = $request->pais;
        $fabricante->save();

        return response()->json([
            'status' => 200,
            'mensagem' => 'Fabricante adicionado com sucesso',
            'fabricante' => new FabricanteResource($fabricante)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Fabricante $fabricante)
    {
        $fabricante = Fabricante::with('fabricante')->find($fabricante->pkfabricante);

        return response() -> json([
            'status' => 200,
            'mensagem' => 'Fabricante retornado',
            'fabricante' => new FabricanteResource($fabricante)
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreFabricanteRequest $request, Fabricante $fabricante)
    {
        $fabricante = Fabricante::find($fabricante->pkfabricante);
        $fabricante->nome = $request->nome_do_fabricante;
        $fabricante->pais = $request->pais;
        $fabricante->update();

        return response()->json([
            'status' => 200,
            'mensagem' => 'Fabricante atualizado com sucesso!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fabricante $fabricante)
    {
        $fabricante->delete();

        return response()->json([
            'status' => 200,
            'mensagem' => 'Fabricante apagado com sucesso'
        ], 200);
    }
}
