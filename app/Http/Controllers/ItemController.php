<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Mesa;

class ItemController extends Controller
{
    //
    public function create(Request $request)
    {
        $success = true;

        $datos = $request->all();

        $mesaValida = Mesa::find($datos['mesa_id']);
        if($mesaValida === null)
            return response()->json(['success' => false, 'message' => "La mesa ingresada no existe."], 400);

        foreach($datos['items'] as $itemRequest)
        {
            $item = Item::make([
                'mesa_id' => $datos['mesa_id'],
                'detalle' => $itemRequest['detalle'],
            ]);

            $tmpSuccess = $item->save();
            if(!$tmpSuccess)
                $success = false;
        }

        return response()->json(['success' => $success], 200);
    }

    public function getPendientes()
    {
        $mesas = Mesa::whereHas
        (
            'items',
            function($query)
            {
                $query->whereListo(0);
            }
        )->get();

        $result = [];

        foreach($mesas as $mesa)
        {
            $result[] = [
                'mesa' => $mesa->id,
                'items' => $mesa->items->where('listo', 0)->values(),
            ];
        }

        return response()->json(['pendientes' => $result], 200);
    }

    public function setOrdenLista($mesaID)
    {
        $mesa = Mesa::find($mesaID);
        if($mesa === null)
            return response()->json(['success' => false, 'message' => "La mesa ingresada no existe."], 400);

        $items = $mesa->items;

        if(count($items) === 0)
            return response()->json(['success' => true, 'message' => "No hay orden para esta mesa."], 204);

        foreach($items as $item)
        {
            $item->listo = true;
            $item->save();
        }

        return response()->json(['itemsOrdenLista' => $items], 200);
    }

    // Otra vez comiteé cambios que correspondían a un caso de uso
    // dentro de un commit que tenía que ver con otra cosa
    // Los cambios correspondientes al caso de uso 4 se encuentran desde la línea 84 hasta la 106. Chasgracias
    public function entregarOrden($mesaID)
    {
        $mesa = Mesa::find($mesaID);
        if($mesa === null)
            return response()->json(['success' => false, 'message' => "La mesa ingresada no existe."], 400);

        $items = $mesa->items;

        if(count($items) === 0)
            return response()->json(['success' => true, 'message' => "No hay orden para esta mesa."], 204);

        // Se fija en todos los ítems de una mesa y verifica que estén todos listos
        foreach($items as $item)
        {
            if(!$item->listo)
                return response()->json(['success' => false, 'message' => "La orden no está lista."], 403);
            $item->entregado = true;
        }
        // Una vez pasado por todos los ítems de una mesa sin errores, aplica los cambios
        $items->each->save();

        return response()->json(['itemsOrdenLista' => $items], 200);
    }

}
