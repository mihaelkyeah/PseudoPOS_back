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

        $mesaValida = Mesa::find($datos['mesa_id']);
        if($mesaValida === null)
            return response()->json(['success' => false, 'message' => "La mesa ingresada no existe 👎"], 400);

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
}
