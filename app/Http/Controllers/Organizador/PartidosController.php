<?php

namespace App\Http\Controllers\Organizador;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartidoOrganizadorRequest;
use App\Models\Partido;
use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Reserva;
use App\Models\Instalacion;
use App\Models\InformacionContacto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        // Ordenamiento por fecha y hora (descendente por defecto)
        $ordenRaw = $request->input('orden', 'desc');
        $esDesc = in_array(strtolower($ordenRaw), ['desc', 'descendente', 'reciente']);
        $orden = $esDesc ? 'desc' : 'asc';

        // Filtro por jornada
        $jornadaSeleccionada = $request->filled('jornada') ? (string) $request->input('jornada') : null;

        // Partidos del torneo seleccionado
        $partidos = collect();
        $jornadas = collect();
        $totalPartidos = 0;
        $totalFinalizados = 0;

        if ($torneoSeleccionado) {
            // Jornadas que existen en el torneo (para el selector)
            $jornadas = Partido::where('torneo_id', $torneoSeleccionado->id)
                ->where('esta_activo', true)
                ->whereNotNull('numero_jornada')
                ->distinct()
                ->orderBy('numero_jornada')
                ->pluck('numero_jornada');

            $query = Partido::with(['equipoLocal', 'equipoVisitante', 'instalacion'])
                ->where('torneo_id', $torneoSeleccionado->id)
                ->where('esta_activo', true);

            if ($jornadaSeleccionada !== null) {
                $query->where('numero_jornada', $jornadaSeleccionada);
            }

            if ($esDesc) {
                $query->orderByDesc('fecha_partido')->orderByDesc('hora_inicio');
            } else {
                $query->orderBy('fecha_partido', 'asc')->orderBy('hora_inicio', 'asc');
            }

            $totalPartidos = (clone $query)->count();
            $totalFinalizados = (clone $query)->where('estado', 'finalizado')->count();

            // Máximo 15 filas por página; el resto se navega con la paginación
            $partidos = $query->paginate(15)->withQueryString();
        }

        return view('organizador.partidos.index', compact(
            'torneosAsignados',
            'torneoSeleccionado',
            'partidos',
            'orden',
            'jornadas',
            'jornadaSeleccionada',
            'totalPartidos',
            'totalFinalizados'
        ));
    }

    /**
     * Genera un PDF vertical con los partidos que coinciden con el torneo y
     * la jornada seleccionados (sin límite de paginación).
     */
    public function imprimir(Request $request)
    {
        $usuarioId = auth()->id();

        $torneosAsignados = Torneo::where('organizador_asignado_id', $usuarioId)
            ->where('esta_activo', true)
            ->orderBy('nombre')
            ->get();

        $torneoSeleccionado = null;
        if ($request->filled('torneo_id')) {
            $torneoSeleccionado = $torneosAsignados->firstWhere('id', $request->torneo_id);
        }
        if (!$torneoSeleccionado) {
            $torneoSeleccionado = $torneosAsignados->first();
        }

        if (!$torneoSeleccionado) {
            return redirect()->route('organizador.partidos.index')
                ->with('error', 'No tienes torneos asignados disponibles para imprimir.');
        }

        $ordenRaw = $request->input('orden', 'desc');
        $esDesc = in_array(strtolower($ordenRaw), ['desc', 'descendente', 'reciente']);

        $jornadaSeleccionada = $request->filled('jornada') ? (string) $request->input('jornada') : null;

        $query = Partido::with(['equipoLocal', 'equipoVisitante'])
            ->where('torneo_id', $torneoSeleccionado->id)
            ->where('esta_activo', true);

        if ($jornadaSeleccionada !== null) {
            $query->where('numero_jornada', $jornadaSeleccionada);
        }

        if ($esDesc) {
            $query->orderByDesc('fecha_partido')->orderByDesc('hora_inicio');
        } else {
            $query->orderBy('fecha_partido')->orderBy('hora_inicio');
        }

        $partidos = $query->get();

        $contacto = InformacionContacto::activa()->first();
        $jornadaTexto = $jornadaSeleccionada !== null
            ? 'Jornada ' . $jornadaSeleccionada
            : 'Todas las jornadas';

        $nombreArchivo = 'partidos-' . Str::slug($torneoSeleccionado->nombre)
            . ($jornadaSeleccionada !== null ? '-jornada-' . $jornadaSeleccionada : '')
            . '.pdf';

        $pdf = Pdf::loadView('organizador.partidos.pdf', compact(
            'torneoSeleccionado',
            'partidos',
            'contacto',
            'jornadaTexto',
            'jornadaSeleccionada'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream($nombreArchivo);
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
        $cruces = collect();
        if ($torneoSeleccionado) {
            $equipos = Equipo::where('torneo_id', $torneoSeleccionado->id)
                ->where('esta_activo', true)
                ->whereIn('estado', ['registrado', 'confirmado'])
                ->orderBy('nombre')
                ->get();

            // Partidos ya programados en este torneo: sirven para no repetir
            // el mismo cruce local vs. visitante (ida y vuelta).
            $cruces = Partido::where('torneo_id', $torneoSeleccionado->id)
                ->where('esta_activo', true)
                ->where('estado', '!=', 'cancelado')
                ->get(['equipo_local_id', 'equipo_visitante_id']);
        }

        $canchaPrincipal = $this->obtenerCanchaPrincipal();

        if (!$canchaPrincipal) {
            return redirect()->route('organizador.partidos.index')
                ->with('error', 'No hay una cancha activa disponible. Contacta al administrador.');
        }

        return view('organizador.partidos.create', compact(
            'torneosAsignados',
            'torneoSeleccionado',
            'equipos',
            'cruces',
            'canchaPrincipal'
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

        // Todos los partidos se registran en la cancha principal
        $canchaPrincipal = $this->obtenerCanchaPrincipal();

        if (!$canchaPrincipal) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No hay una cancha activa disponible. Contacta al administrador.');
        }

        // Duración definida por el organizador (hora de inicio y hora de fin)
        $horaInicio = Carbon::parse($datos['hora_inicio']);
        $horaFin = Carbon::parse($datos['hora_fin']);
        $totalHoras = round($horaInicio->diffInMinutes($horaFin) / 60, 2);

        // Verificar conflictos de horario en la cancha principal
        $conflicto = $this->verificarConflictoHorario(
            $canchaPrincipal->id,
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
                'hora_fin' => $datos['hora_fin'],
                'instalacion_id' => $canchaPrincipal->id,
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
                'instalacion_id' => $canchaPrincipal->id,
                'nombre_cliente' => $torneo->nombre . ': ' . $equipoLocal->nombre . ' vs ' . $equipoVisitante->nombre,
                'telefono_cliente' => $equipoLocal->telefono_capitan ?? 'N/A',
                'email_cliente' => $equipoLocal->email_capitan ?? null,
                'fecha_reserva' => $datos['fecha_partido'],
                'hora_inicio' => $datos['hora_inicio'],
                'hora_fin' => $datos['hora_fin'],
                'total_horas' => $totalHoras,
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

        $canchaPrincipal = $this->obtenerCanchaPrincipal() ?? $partido->instalacion;

        return view('organizador.partidos.edit', compact(
            'partido',
            'torneo',
            'torneosAsignados',
            'equipos',
            'canchaPrincipal'
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

        // Todos los partidos se registran en la cancha principal
        $canchaPrincipal = $this->obtenerCanchaPrincipal();
        $instalacionId = $canchaPrincipal?->id ?? $partido->instalacion_id;

        // Duración definida por el organizador (hora de inicio y hora de fin)
        $horaInicio = Carbon::parse($datos['hora_inicio']);
        $horaFin = Carbon::parse($datos['hora_fin']);
        $totalHoras = round($horaInicio->diffInMinutes($horaFin) / 60, 2);

        // Verificar conflictos (excluyendo el partido actual)
        $conflicto = $this->verificarConflictoHorario(
            $instalacionId,
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
                'hora_fin' => $datos['hora_fin'],
                'instalacion_id' => $instalacionId,
                'numero_jornada' => $datos['numero_jornada'] ?? null,
                'nombre_grupo' => $datos['nombre_grupo'] ?? null,
                'arbitro' => $datos['arbitro'] ?? null,
                'notas' => $datos['notas'] ?? null,
                'estado' => $datos['estado'],
                'esta_activo' => true,
                'actualizado_por' => auth()->id(),
            ]);

            // Actualizar la reserva asociada
            $reserva = Reserva::where('partido_id', $partido->id)->first();
            if ($reserva) {
                $equipoLocal = Equipo::find($datos['equipo_local_id']);
                $equipoVisitante = Equipo::find($datos['equipo_visitante_id']);

                $estadoReserva = match($datos['estado']) {
                    'cancelado' => 'cancelada',
                    'finalizado' => 'completada',
                    default => 'confirmada',
                };

                $reserva->update([
                    'instalacion_id' => $instalacionId,
                    'nombre_cliente' => $torneo->nombre . ': ' . $equipoLocal->nombre . ' vs ' . $equipoVisitante->nombre,
                    'telefono_cliente' => $equipoLocal->telefono_capitan ?? 'N/A',
                    'fecha_reserva' => $datos['fecha_partido'],
                    'hora_inicio' => $datos['hora_inicio'],
                    'hora_fin' => $datos['hora_fin'],
                    'total_horas' => $totalHoras,
                    'estado' => $estadoReserva,
                    'esta_activo' => $datos['estado'] !== 'cancelado',
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

        if ($partido->estado === 'finalizado') {
            return redirect()->back()
                ->with('error', 'Este partido ya está finalizado. Usa la acción "Editar marcador" para corregir el resultado.');
        }

        if ($partido->estado === 'cancelado') {
            return redirect()->back()
                ->with('error', 'No se puede registrar resultado en un partido cancelado. Edita el partido para reactivarlo primero.');
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
     * Actualiza únicamente el marcador de un partido finalizado.
     * Las estadísticas de los equipos se recalculan automáticamente.
     */
    public function actualizarMarcador(Request $request, Partido $partido)
    {
        // Verificar permisos
        $torneo = Torneo::where('id', $partido->torneo_id)
            ->where('organizador_asignado_id', auth()->id())
            ->first();

        if (!$torneo) {
            return redirect()->route('organizador.partidos.index')
                ->with('error', 'No tienes permiso para editar el marcador de este partido.');
        }

        if ($partido->estado !== 'finalizado') {
            return redirect()->back()
                ->with('error', 'Solo puedes editar el marcador de un partido finalizado.');
        }

        $request->validate([
            'marcador_local' => ['required', 'integer', 'min:0', 'max:99'],
            'marcador_visitante' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        try {
            DB::beginTransaction();

            // Revertir las estadísticas calculadas con el marcador anterior
            $this->revertirEstadisticasEquipos($partido);

            // Actualizar únicamente el marcador
            $partido->update([
                'marcador_local' => $request->marcador_local,
                'marcador_visitante' => $request->marcador_visitante,
                'actualizado_por' => auth()->id(),
            ]);

            // Recalcular estadísticas con el nuevo marcador
            $this->actualizarEstadisticasEquipos($partido);

            DB::commit();

            return redirect()->route('organizador.partidos.index', ['torneo_id' => $partido->torneo_id])
                ->with('success', 'Marcador corregido exitosamente. Las estadísticas de los equipos fueron recalculadas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al editar el marcador: ' . $e->getMessage());
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

    /**
     * Revierte las estadísticas de los equipos calculadas con un marcador previo.
     */
    private function revertirEstadisticasEquipos(Partido $partido)
    {
        if ($partido->marcador_local === null || $partido->marcador_visitante === null) {
            return;
        }

        $equipoLocal = Equipo::find($partido->equipo_local_id);
        $equipoVisitante = Equipo::find($partido->equipo_visitante_id);

        if (!$equipoLocal || !$equipoVisitante) {
            return;
        }

        // Revertir partidos jugados
        $equipoLocal->decrement('partidos_jugados');
        $equipoVisitante->decrement('partidos_jugados');

        // Revertir goles
        $equipoLocal->decrement('goles_favor', $partido->marcador_local);
        $equipoLocal->decrement('goles_contra', $partido->marcador_visitante);
        $equipoVisitante->decrement('goles_favor', $partido->marcador_visitante);
        $equipoVisitante->decrement('goles_contra', $partido->marcador_local);

        // Revertir resultado y puntos
        if ($partido->marcador_local > $partido->marcador_visitante) {
            $equipoLocal->decrement('partidos_ganados');
            $equipoLocal->decrement('puntos', 3);
            $equipoVisitante->decrement('partidos_perdidos');
        } elseif ($partido->marcador_visitante > $partido->marcador_local) {
            $equipoVisitante->decrement('partidos_ganados');
            $equipoVisitante->decrement('puntos', 3);
            $equipoLocal->decrement('partidos_perdidos');
        } else {
            $equipoLocal->decrement('partidos_empatados');
            $equipoLocal->decrement('puntos', 1);
            $equipoVisitante->decrement('partidos_empatados');
            $equipoVisitante->decrement('puntos', 1);
        }
    }

    /**
     * Obtiene la cancha principal activa (o la primera cancha activa disponible).
     */
    private function obtenerCanchaPrincipal(): ?Instalacion
    {
        return Instalacion::where('esta_activo', true)
                ->where('nombre', 'like', '%principal%')
                ->orderBy('id')
                ->first()
            ?? Instalacion::where('esta_activo', true)
                ->orderBy('id')
                ->first();
    }
}