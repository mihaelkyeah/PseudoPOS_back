<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Mesa;

class PedidoController extends Controller
{
    //
    public function create(Request $request)
    {
        // return response()->json(['request' => print_r($request->all(), true)]);
        $success = true;

        $datos = $request->all();

        if((string)$datos['mesa_id'] !== (string)(int)$datos['mesa_id'] || (int)$datos['mesa_id'] < 1)
            return response()->json(['success' => false, 'message' => "Ingrese un ID de mesa válido."], 400);

        $mesaValida = Mesa::find($datos['mesa_id']);
        if($mesaValida === null)
            return response()->json(['success' => false, 'message' => "La mesa ingresada no existe."], 400);

        foreach($datos['pedidos'] as $pedidoRequest)
        {
            $pedido = Pedido::make([
                'mesa_id' => $datos['mesa_id'],
                'detalle' => $pedidoRequest['detalle'],
            ]);

            $tmpSuccess = $pedido->save();
            if(!$tmpSuccess)
                $success = false;
        }

        return response()->json(['success' => $success], 200);
    }

    public function getPendientes()
    {
        // $result = Pedido::whereLista(0)->orderBy('mesa_id')->get();
        $mesas = Mesa::whereHas
        (
            'pedidos',
            function($query)
            {
                $query->whereLista(0);
            }
        )->get();

        $result = [];

        foreach($mesas as $mesa)
        {
            $result[] = [
                'mesa' => $mesa->id,
                'pedidos' => $mesa->pedidos->where('lista', 0)->values(),
            ];
        }

        return response()->json(['pendientes' => $result], 200);
    }

    public function setOrdenLista($mesaID)
    {
        if((string)$mesaID !== (string)(int)$mesaID || (int)$mesaID < 1)
            return response()->json(['success' => false, 'message' => "Ingrese un ID de mesa válido."], 400);

        $mesa = Mesa::find($mesaID);

        if($mesa === null)
            return response()->json(['success' => false, 'message' => "La mesa ingresada no existe."], 400);

        $pedidos = $mesa->pedidos;

        if(count($pedidos) === 0)
            return response()->json(['success' => false, 'message' => "No hay orden para esta mesa."], 404);

        $result = [];

        foreach($pedidos as $pedido)
        {
            $pedido->update(['lista' => true]);
            $result[] = $pedido;
        }

        return response()->json(['itemsOrdenLista' => $result], 200);
    }




}
