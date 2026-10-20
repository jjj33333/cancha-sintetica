<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnuncioRequest;
use App\Models\Anuncio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnunciosController extends Controller
{
    /**
     * Muestra la lista de anuncios.
     */
    public function index(Request $request)
    {
        $query = Anuncio::with('creadoPorUsuario')
            ->orderByDesc('created_at');

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por prioridad
        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        // Filtro por estado de publicación
        if ($request->filled('estado_publicacion')) {
            if ($request->estado_publicacion === 'publicado') {
                $query->where('esta_publicado', true);
            } elseif ($request->estado_publicacion === 'borrador') {
                $query->where('esta_publicado', false);
            }
        }

        // Filtro por visibilidad pública
        if ($request->filled('visibilidad')) {
            $query->where('mostrar_en_publico', $request->visibilidad === 'visible');
        }

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('titulo', 'like', "%{$buscar}%")
                  ->orWhere('contenido', 'like', "%{$buscar}%");
            });
        }

        $anuncios = $query->paginate(10);

        return view('admin.anuncios.index', compact('anuncios'));
    }

    /**
     * Muestra el formulario para crear un nuevo anuncio.
     */
    public function create()
    {
        return view('admin.anuncios.create');
    }

    /**
     * Almacena un nuevo anuncio.
     */
    public function store(StoreAnuncioRequest $request)
    {
        $datos = $request->validated();

        // Generar slug si no existe
        if (empty($datos['slug'])) {
            $datos['slug'] = Str::slug($datos['titulo']) . '-' . Str::random(5);
        }

        // Manejar campos booleanos
        $datos['esta_publicado'] = $request->boolean('esta_publicado', true);
        $datos['mostrar_en_publico'] = $request->boolean('mostrar_en_publico', true);
        $datos['esta_activo'] = $request->boolean('esta_activo', true);

        // Si se marca para mostrar en público o está publicado, asegurar estado publicado y fecha
        if ($datos['mostrar_en_publico']) {
            $datos['esta_publicado'] = true;
        }

        if ($datos['esta_publicado'] && empty($datos['publicado_en'])) {
            $datos['publicado_en'] = now();
        }

        // Manejar subida de imagen
        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        }

        $datos['creado_por'] = auth()->id();

        Anuncio::create($datos);

        return redirect()->route('admin.anuncios.index')
            ->with('success', 'Anuncio creado exitosamente.');
    }

    /**
     * Muestra los detalles de un anuncio.
     */
    public function show(Anuncio $anuncio)
    {
        $anuncio->load('creadoPorUsuario');
        return view('admin.anuncios.show', compact('anuncio'));
    }

    /**
     * Muestra el formulario para editar un anuncio.
     */
    public function edit(Anuncio $anuncio)
    {
        return view('admin.anuncios.edit', compact('anuncio'));
    }

    /**
     * Actualiza un anuncio.
     */
    public function update(StoreAnuncioRequest $request, Anuncio $anuncio)
    {
        $datos = $request->validated();

        // Manejar slug
        if (empty($datos['slug']) || $datos['slug'] === $anuncio->slug) {
            unset($datos['slug']);
        }

        // Manejar campos booleanos
        $datos['esta_publicado'] = $request->boolean('esta_publicado', false);
        $datos['mostrar_en_publico'] = $request->boolean('mostrar_en_publico', false);
        $datos['esta_activo'] = $request->boolean('esta_activo', true);

        // Si se marca para mostrar en público, asegurar que esté publicado
        if ($datos['mostrar_en_publico']) {
            $datos['esta_publicado'] = true;
        }

        // Si se marca como publicado y no tiene fecha, usar ahora
        if ($datos['esta_publicado'] && empty($anuncio->publicado_en) && empty($datos['publicado_en'])) {
            $datos['publicado_en'] = now();
        }

        // Manejar subida de nueva imagen
        if ($request->hasFile('imagen')) {
            if ($anuncio->imagen) {
                Storage::disk('public')->delete($anuncio->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        }

        $datos['actualizado_por'] = auth()->id();

        $anuncio->update($datos);

        return redirect()->route('admin.anuncios.index')
            ->with('success', 'Anuncio actualizado exitosamente.');
    }

    /**
     * Cambia la visibilidad pública del anuncio (Toggle).
     */
    public function toggleVisibilidad(Anuncio $anuncio)
    {
        $nuevoEstado = !$anuncio->mostrar_en_publico;
        $updates = [
            'mostrar_en_publico' => $nuevoEstado,
            'actualizado_por' => auth()->id(),
        ];

        // Si se hace visible en la web pública, asegurar que esté publicado y con fecha
        if ($nuevoEstado) {
            $updates['esta_publicado'] = true;
            if (empty($anuncio->publicado_en)) {
                $updates['publicado_en'] = now();
            }
        }

        $anuncio->update($updates);

        return back()->with('success', 'Visibilidad del anuncio actualizada.');
    }

    /**
     * Elimina (desactiva) un anuncio.
     */
    public function destroy(Anuncio $anuncio)
    {
        // Eliminar imagen si existe
        if ($anuncio->imagen) {
            Storage::disk('public')->delete($anuncio->imagen);
        }

        $anuncio->update([
            'esta_activo' => false,
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()->route('admin.anuncios.index')
            ->with('success', 'Anuncio eliminado/desactivado exitosamente.');
    }
}