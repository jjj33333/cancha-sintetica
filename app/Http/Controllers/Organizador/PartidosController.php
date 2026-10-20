<?php

namespace App\Http\Controllers\Organizador;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartidoOrganizadorRequest;
use App\Models\Partido;
use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Reserva;
use App\Models\Instalacion;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PartidosController extends Controller
{
    /**
     * Muestra la lista de partidos de los torneos asignados.
     */
    public function index(Request $request)
    {
        $usuarioId = auth()->id();

        // Obtener torneos asignados al organizador
        $torneosAsignados = Torneo::where('organizador_asignado_id', $usuarioId)
            ->where('esta_activo', true)
            ->orderBy('nombre')
            ->get();

        // Torneo seleccionado
        $torneoSeleccionado = null;
        if ($request->filled('torneo_id')) {
            $torneoSeleccionado = $torneosAsignados->firstWhere('id', $request->torneo_id);
        }
        if (!$torneoSeleccionado && $torneosAsignados->count() > 0) {
            $torneoSeleccionado = $torneosAsignados->first();
        }

        // Partidos del torneo seleccionado
        $partidos = collect();
        if ($torneoSeleccionado) {
            $partidos = Partido::with(['equipoLocal', 'equipoVisitante', 'instalacion'])
                ->where('torneo_id', $torneoSeleccionado->id)
                ->where('esta_activo', true)
                ->orderBy('fecha_partido')
                ->orderBy('hora_inicio')
                ->get();
        }

        return view('organizador.partidos.index', compact(
            'torneosAsignados',
            'torneoSeleccionado',
            'partidos'
        ));
    }

    /**
     * Muestra el formulario para crear un nuevo partido.
     */
    public function create(Request $request)
    {
        $usuarioId = auth()->id();

        $torneosAsignados = Torneo::where('organizador_asignado_id', $usuarioId)
            ->where('esta_activo', true)
            ->whereIn('estado', ['borrador', 'inscripciones_abiertas', 'en_curso'])
            ->orderBy('nombre')
            ->get();

        $torneoSeleccionado = null;
        if ($request->filled('torneo_id')) {
            $torneoSeleccionado = $torneosAsignados->firstWhere('id', $request->torneo_id);
        }
        if (!$torneoSeleccionado && $torneosAsignados->count() > 0) {
            $torneoSeleccionado = $torneosAsignados->first();
        }

        $equipos = collect();
        if ($torneoSeleccionado) {
            $equipos = Equipo::where('torneo_id', $torneoSeleccionado->id)
                ->where('esta_activo', true)
                ->whereIn('estado', ['registrado', 'confirmado'])
                ->orderBy('nombre')
                ->get();
        }

        $instalaciones = Instalacion::where('esta_activo', true)->orderBy('nombre')->get();

        return view('organizador.partidos.create', compact(
            'torneosAsignados',
            'torneoSeleccionado',
            'equipos',
            'instalaciones'
        ));
    }

    /**
     * Almacena un nuevo partido y crea la reserva automáticamente.
     */
    public function store(StorePartidoOrganizadorRequest $request)
    {
        $datos = $request->validated();

        // Verificar que el torneo esté asignado al organizador
        $torneo = Torneo::where('id', $datos['torneo_id'])
            ->where('organizador_asignado_id', auth()->id())
            ->first();

        if (!$torneo) {
            return redirect()->route('organizador.partidos.index')
                ->with('error', 'No tienes permiso para crear partidos en este torneo.');
        }

        // Calcular hora_fin (asumiendo 2 horas de duración por partido)
        $horaInicio = Carbon::parse($datos['hora_inicio']);
        $datos['hora_fin'] = $horaInicio->copy()->addHours(2)->format('H:i');

        // Verificar conflictos de horario en la cancha seleccionada
        $conflicto = $this->verificarConflictoHorario(
            $datos['instalacion_id'],
            $datos['fecha_partido'],
            $datos['hora_inicio'],
            $datos['hora_fin']
        );

        if ($conflicto) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ya existe una reserva o partido en esa cancha en el horario seleccionado.');
        }

        try {
            DB::beginTransaction();

            // Crear el partido
            $partido = Partido::create([
                'torneo_id' => $datos['torneo_id'],
                'equipo_local_id' => $datos['equipo_local_id'],
                'equipo_visitante_id' => $datos['equipo_visitante_id'],
                'fecha_partido' => $datos['fecha_partido'],
                'hora_inicio' => $datos['hora_inicio'],
                'instalacion_id' => $datos['instalacion_id'],
                'numero_jornada' => $datos['numero_jornada'] ?? null,
                'nombre_grupo' => $datos['nombre_grupo'] ?? null,
                'arbitro' => $datos['arbitro'] ?? null,
                'notas' => $datos['notas'] ?? null,
                'estado' => $datos['estado'],
                'reservado_automatico' => 1,
                'esta_activo' => true,
                'creado_por' => auth()->id(),
            ]);

            // Crear la reserva automáticamente
            $equipoLocal = Equipo::find($datos['equipo_local_id']);
            $equipoVisitante = Equipo::find($datos['equipo_visitante_id']);

            Reserva::create([
                'codigo_reserva' => 'TOR-' . strtoupper(substr(md5(uniqid()), 0, 6)),
                'instalacion_id' => $datos['instalacion_id'],
                'nombre_cliente' => $torneo->nombre . ': ' . $equipoLocal->nombre . ' vs ' . $equipoVisitante->nombre,
                'telefono_cliente' => $equipoLocal->telefono_capitan ?? 'N/A',
                'email_cliente' => $equipoLocal->email_capitan ?? null,
                'fecha_reserva' => $datos['fecha_partido'],
                'hora_inicio' => $datos['hora_inicio'],
                'hora_fin' => $datos['hora_fin'],
                'total_horas' => 2,
                'precio_total' => 0, // Los partidos de torneo no tienen costo de reserva
                'estado' => 'confirmada',
                'tipo_reserva' => 'torneo',
                'partido_id' => $partido->id,
                'torneo_id' => $datos['torneo_id'],
                'estado_pago' => 'pagado',
                'metodo_pago' => 'otro',
                'notas' => 'Reserva automática generada al programar partido del torneo ' . $torneo->nombre,
                'esta_activo' => true,
                'creado_por' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('organizador.partidos.index', ['torneo_id' => $datos['torneo_id']])
                ->with('success', 'Partido programado exitosamente. La cancha se reservó automáticamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el partido: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un partido.
     */
    public function show(Partido $partido)
    {
        // Verificar que el partido pertenezca a un torneo del organizador
        $torneo = Torneo::where('id', $partido->torneo_id)
            ->where('organizador_asignado_id', auth()->id())
            ->first();

        if (!$torneo) {
            return redirect()->route('organizador.partidos.index')
                ->with('error', 'No tienes permiso para ver este partido.');
        }

        $partido->load(['torneo', 'equipoLocal', 'equipoVisitante', 'instalacion']);

        // Buscar la reserva asociada
        $reservaAsociada = Reserva::where('partido_id', $partido->id)->first();

        return view('organizador.partidos.show', compact('partido', 'torneo', 'reservaAsociada'));
    }

    /**
     * Muestra el formulario para editar un partido.
     */
    public function edit(Partido $partido)
    {
        // Verificar permisos
        $torneo = Torneo::where('id', $partido->torneo_id)
            ->where('organizador_asignado_id', auth()->id())
            ->first();

        if (!$torneo) {
            return redirect()->route('organizador.partidos.index')
                ->with('error', 'No tienes permiso para editar este partido.');
        }

        // No permitir editar partidos finalizados
        if ($partido->estado === 'finalizado') {
            return redirect()->route('organizador.partidos.show', $partido)
                ->with('error', 'No puedes editar un partido ya finalizado.');
        }

        $torneosAsignados = Torneo::where('organizador_asignado_id', auth()->id())
            ->where('esta_activo', true)
            ->orderBy('nombre')
            ->get();

        $equipos = Equipo::where('torneo_id', $partido->torneo_id)
            ->where('esta_activo', true)
            ->whereIn('estado', ['registrado', 'confirmado'])
            ->orderBy('nombre')
            ->get();

        $instalaciones = Instalacion::where('esta_activo', true)->orderBy('nombre')->get();

        return view('organizador.partidos.edit', compact(
            'partido',
            'torneo',
            'torneosAsignados',
            'equipos',
            'instalaciones'
        ));
    }

    /**
     * Actualiza un partido.
     */
    public function update(StorePartidoOrganizadorRequest $request, Partido $partido)
    {
        // Verificar permisos
        $torneo = Torneo::where('id', $partido->torneo_id)
            ->where('organizador_asignado_id', auth()->id())
            ->first();

        if (!$torneo) {
            return redirect()->route('organizador.partidos.index')
                ->with('error', 'No tienes permiso para editar este partido.');
        }

        // No permitir editar partidos finalizados
        if ($partido->estado === 'finalizado') {
            return redirect()->route('organizador.partidos.show', $partido)
                ->with('error', 'No puedes editar un partido ya finalizado.');
        }

        $datos = $request->validated();

        // Calcular hora_fin
        $horaInicio = Carbon::parse($datos['hora_inicio']);
        $datos['hora_fin'] = $horaInicio->copy()->addHours(2)->format('H:i');

        // Verificar conflictos (excluyendo el partido actual)
        $conflicto = $this->verificarConflictoHorario(
            $datos['instalacion_id'],
            $datos['fecha_partido'],
            $datos['hora_inicio'],
            $datos['hora_fin'],
            $partido->id
        );

        if ($conflicto) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ya existe una reserva o partido en esa cancha en el horario seleccionado.');
        }

        try {
            DB::beginTransaction();

            // Actualizar el partido
            $partido->update([
                'torneo_id' => $datos['torneo_id'],
                'equipo_local_id' => $datos['equipo_local_id'],
                'equipo_visitante_id' => $datos['equipo_visitante_id'],
                'fecha_partido' => $datos['fecha_partido'],
                'hora_inicio' => $datos['hora_inicio'],
                'instalacion_id' => $datos['instalacion_id'],
                'numero_jornada' => $datos['numero_jornada'] ?? null,
                'nombre_grupo' => $datos['nombre_grupo'] ?? null,
                'arbitro' => $datos['arbitro'] ?? null,
                'notas' => $datos['notas'] ?? null,
                'estado' => $datos['estado'],
                'actualizado_por' => auth()->id(),
            ]);

            // Actualizar la reserva asociada
            $reserva = Reserva::where('partido_id', $partido->id)->first();
            if ($reserva) {
                $equipoLocal = Equipo::find($datos['equipo_local_id']);
                $equipoVisitante = Equipo::find($datos['equipo_visitante_id']);

                $reserva->update([
                    'instalacion_id' => $datos['instalacion_id'],
                    'nombre_cliente' => $torneo->nombre . ': ' . $equipoLocal->nombre . ' vs ' . $equipoVisitante->nombre,
                    'telefono_cliente' => $equipoLocal->telefono_capitan ?? 'N/A',
                    'fecha_reserva' => $datos['fecha_partido'],
                    'hora_inicio' => $datos['hora_inicio'],
                    'hora_fin' => $datos['hora_fin'],
                    'actualizado_por' => auth()->id(),
                ]);
            }

            DB::commit();

            return redirect()->route('organizador.partidos.index', ['torneo_id' => $datos['torneo_id']])
                ->with('success', 'Partido actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el partido: ' . $e->getMessage());
        }
    }

    /**
     * Registra el resultado de un partido (marcador).
     */
    public function registrarResultado(Request $request, Partido $partido)
    {
        // Verificar permisos
        $torneo = Torneo::where('id', $partido->torneo_id)
            ->where('organizador_asignado_id', auth()->id())
            ->first();

        if (!$torneo) {
            return redirect()->route('organizador.partidos.index')
                ->with('error', 'No tienes permiso para registrar resultados en este partido.');
        }

        $request->validate([
            'marcador_local' => ['required', 'integer', 'min:0', 'max:99'],
            'marcador_visitante' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        try {
            DB::beginTransaction();

            // Actualizar el partido con el marcador
            $partido->update([
                'marcador_local' => $request->marcador_local,
                'marcador_visitante' => $request->marcador_visitante,
                'estado' => 'finalizado',
                'actualizado_por' => auth()->id(),
            ]);

            // Actualizar estadísticas de los equipos
            $this->actualizarEstadisticasEquipos($partido);

            // Actualizar la reserva asociada a "completada"
            $reserva = Reserva::where('partido_id', $partido->id)->first();
            if ($reserva) {
                $reserva->update([
                    'estado' => 'completada',
                    'actualizado_por' => auth()->id(),
                ]);
            }

            DB::commit();

            return redirect()->route('organizador.partidos.index', ['torneo_id' => $partido->torneo_id])
                ->with('success', 'Resultado registrado exitosamente. Las estadísticas de los equipos fueron actualizadas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al registrar el resultado: ' . $e->getMessage());
        }
    }

    /**
     * Elimina (desactiva) un partido y su reserva asociada.
     */
    public function destroy(Partido $partido)
    {
        // Verificar permisos
        $torneo = Torneo::where('id', $partido->torneo_id)
            ->where('organizador_asignado_id', auth()->id())
            ->first();

        if (!$torneo) {
            return redirect()->route('organizador.partidos.index')
                ->with('error', 'No tienes permiso para eliminar este partido.');
        }

        // No permitir eliminar partidos finalizados
        if ($partido->estado === 'finalizado') {
            return redirect()->route('organizador.partidos.index', ['torneo_id' => $partido->torneo_id])
                ->with('error', 'No puedes eliminar un partido ya finalizado.');
        }

        try {
            DB::beginTransaction();

            // Desactivar la reserva asociada
            $reserva = Reserva::where('partido_id', $partido->id)->first();
            if ($reserva) {
                $reserva->update([
                    'esta_activo' => false,
                    'estado' => 'cancelada',
                    'actualizado_por' => auth()->id(),
                ]);
            }

            // Desactivar el partido
            $partido->update([
                'esta_activo' => false,
                'estado' => 'cancelado',
                'actualizado_por' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('organizador.partidos.index', ['torneo_id' => $partido->torneo_id])
                ->with('success', 'Partido cancelado/eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al eliminar el partido: ' . $e->getMessage());
        }
    }

    /**
     * Verifica si hay conflictos de horario en una cancha.
     */
    private function verificarConflictoHorario($instalacionId, $fecha, $horaInicio, $horaFin, $excluirPartidoId = null)
    {
        $query = Reserva::where('instalacion_id', $instalacionId)
            ->where('fecha_reserva', $fecha)
            ->where('esta_activo', true)
            ->where('estado', '!=', 'cancelada')
            ->where('hora_inicio', '<', $horaFin)
            ->where('hora_fin', '>', $horaInicio);

        if ($excluirPartidoId) {
            $query->where(function ($q) use ($excluirPartidoId) {
                $q->whereNull('partido_id')
                  ->orWhere('partido_id', '!=', $excluirPartidoId);
            });
        }

        return $query->exists();
    }

    /**
     * Actualiza las estadísticas de los equipos después de un partido finalizado.
     */
    private function actualizarEstadisticasEquipos(Partido $partido)
    {
        $equipoLocal = Equipo::find($partido->equipo_local_id);
        $equipoVisitante = Equipo::find($partido->equipo_visitante_id);

        if (!$equipoLocal || !$equipoVisitante) {
            return;
        }

        // Incrementar partidos jugados
        $equipoLocal->increment('partidos_jugados');
        $equipoVisitante->increment('partidos_jugados');

        // Actualizar goles
        $equipoLocal->increment('goles_favor', $partido->marcador_local);
        $equipoLocal->increment('goles_contra', $partido->marcador_visitante);
        $equipoVisitante->increment('goles_favor', $partido->marcador_visitante);
        $equipoVisitante->increment('goles_contra', $partido->marcador_local);

        // Determinar ganador y actualizar puntos
        if ($partido->marcador_local > $partido->marcador_visitante) {
            // Gana local
            $equipoLocal->increment('partidos_ganados');
            $equipoLocal->increment('puntos', 3);
            $equipoVisitante->increment('partidos_perdidos');
        } elseif ($partido->marcador_visitante > $partido->marcador_local) {
            // Gana visitante
            $equipoVisitante->increment('partidos_ganados');
            $equipoVisitante->increment('puntos', 3);
            $equipoLocal->increment('partidos_perdidos');
        } else {
            // Empate
            $equipoLocal->increment('partidos_empatados');
            $equipoLocal->increment('puntos', 1);
            $equipoVisitante->increment('partidos_empatados');
            $equipoVisitante->increment('puntos', 1);
        }
    }
}