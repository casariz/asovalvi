<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StateController extends Controller
{
    public function tasks()
    {
        $status = [
            'Pendiente',
            'Asignada',
            'Completada',
            'Rechazada'
        ];

        return response()->json(['status' => $status]);
    }

    public function meetings()
    {
        $status = [
            'Creado',
            'Realizado'
        ];

        return response()->json(['status' => $status]);
    }

    public function obligations()
    {
        $status = [
            'Inactiva',
            'Activa',
            'Pendiente',
            'Vencida'
        ];

        return response()->json(['status' => $status]);
    }
}
