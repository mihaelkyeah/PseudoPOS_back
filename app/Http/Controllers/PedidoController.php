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
        $success = true;

        $datos = $request->all();

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
        $mesa = Mesa::find($mesaID);
        if($mesa === null)
            return response()->json(['success' => false, 'message' => "La mesa ingresada no existe."], 400);

        $pedidos = $mesa->pedidos;

        if(count($pedidos) === 0)
            return response()->json(['success' => true, 'message' => "No hay orden para esta mesa."], 204);

        foreach($pedidos as $pedido)
        {
            $pedido->update(['lista' => true]);
        }

        return response()->json(['itemsOrdenLista' => $pedidos], 200);
    }

    public function entregarOrden($mesaID)
    {

    }

}
