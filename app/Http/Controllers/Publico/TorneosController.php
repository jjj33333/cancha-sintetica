<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Torneo;
use App\Models\Partido;
use App\Models\InformacionContacto;
use Illuminate\Http\Request;

class TorneosController extends Controller
{
    public function index(Request $request)
    {
        $contacto = InformacionContacto::activa()->first();

        // Torneos públicos (en curso o con inscripciones abiertas)
        $torneos = Torneo::activos()
            ->publicos()
            ->orderByDesc('estado')
            ->get();

        // También incluir borradores como "Próximamente"
        $torneosProximos = Torneo::activos()
            ->where('estado', 'borrador')
            ->get();

        $torneos = $torneos->merge($torneosProximos);

        // Partido próximo más cercano (define el campeonato por defecto del calendario)
        $proximoPartido = Partido::activos()
            ->proximos()
            ->orderBy('fecha_partido')
            ->orderBy('hora_inicio')
            ->first();

        // Campeonato seleccionado en el filtro del calendario.
        // Por defecto se usa el campeonato con la jornada más próxima a jugarse.
        $torneoCalendarioSeleccionado = null;

        if ($request->filled('torneo_id')) {
            $torneoCalendarioSeleccionado = $torneos->firstWhere('id', $request->integer('torneo_id'));
        }

        if (!$torneoCalendarioSeleccionado) {
            $torneoCalendarioSeleccionado = $torneos->firstWhere('id', $proximoPartido?->torneo_id)
                ?? $torneos->first();
        }

        // Próximos partidos programados del campeonato seleccionado
        $partidosProximos = collect();

        if ($torneoCalendarioSeleccionado) {
            $partidosProximos = Partido::activos()
                ->proximos()
                ->where('torneo_id', $torneoCalendarioSeleccionado->id)
                ->with(['torneo', 'equipoLocal', 'equipoVisitante'])
                ->get();
        }

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

        // Pestaña activa al ingresar (permite abrir el calendario desde otro apartado)
        $tabInicial = in_array($request->input('tab'), ['activos', 'calendario', 'posiciones'], true)
            ? $request->input('tab')
            : 'posiciones';

        return view('publico.torneos', compact(
            'contacto',
            'torneos',
            'partidosProximos',
            'torneoCalendarioSeleccionado',
            'tablasPosiciones',
            'tabInicial'
        ));
    }
}
