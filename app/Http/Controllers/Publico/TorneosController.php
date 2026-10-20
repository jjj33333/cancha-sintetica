<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Torneo;
use App\Models\Partido;
use App\Models\InformacionContacto;

class TorneosController extends Controller
{
    public function index()
    {
        $contacto = InformacionContacto::activa()->first();

        // Torneos públicos (en curso o con inscripciones abiertas)
        $torneos = Torneo::activos()
            ->publicos()
            ->withCount('equipos')
            ->with('organizador')
            ->orderByDesc('estado')
            ->get();

        // También incluir borradores como "Próximamente"
        $torneosProximos = Torneo::activos()
            ->where('estado', 'borrador')
            ->withCount('equipos')
            ->get();

        $torneos = $torneos->merge($torneosProximos);

        // Próximos partidos programados
        $partidosProximos = Partido::activos()
            ->proximos()
            ->with(['torneo', 'equipoLocal', 'equipoVisitante', 'instalacion'])
            ->take(10)
            ->get();

        // Partidos finalizados recientes
        $partidosFinalizados = Partido::activos()
            ->finalizados()
            ->with(['torneo', 'equipoLocal', 'equipoVisitante', 'instalacion'])
            ->take(5)
            ->get();

        // Tablas de posiciones (equipos ordenados por puntos)
        $tablasPosiciones = [];
        foreach ($torneos as $torneo) {
            if ($torneo->estado === 'en_curso' || $torneo->estado === 'inscripciones_abiertas') {
                $tablasPosiciones[$torneo->id] = $torneo->equipos()
                    ->where('esta_activo', true)
                    ->orderByDesc('puntos')
                    ->orderByDesc('goles_favor')
                    ->get();
            }
        }

        return view('publico.torneos', compact(
            'contacto',
            'torneos',
            'partidosProximos',
            'partidosFinalizados',
            'tablasPosiciones'
        ));
    }
}