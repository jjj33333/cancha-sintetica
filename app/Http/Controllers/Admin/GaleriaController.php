<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGaleriaImagenRequest;
use App\Models\GaleriaImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriaController extends Controller
{
    /**
     * Muestra la galería.
     */
    public function index(Request $request)
    {
        $query = GaleriaImagen::with('subidoPorUsuario')
            ->activos()
            ->ordenados();

        // Filtro por categoría
        if ($request->filled('categoria')) {
            $query->deCategoria($request->categoria);
        }

        // Filtro por destacados
        if ($request->filled('destacados')) {
            $query->destacados();
        }

        // Búsqueda por título
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where('titulo', 'like', "%{$buscar}%");
        }

        $imagenes = $query->paginate(12);

        return view('admin.galeria.index', compact('imagenes'));
    }

    /**
     * Muestra el formulario para subir.
     */
    public function create()
    {
        return view('admin.galeria.create');
    }

    /**
     * Almacena una nueva imagen/video.
     */
    public function store(StoreGaleriaImagenRequest $request)
    {
        $datos = $request->validated();

        // Manejar subida de archivo
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $path = $file->store('galeria', 'public');
            
            $datos['ruta_archivo'] = $path;
            $datos['tipo_mime'] = $file->getMimeType();
            $datos['tamano_archivo'] = $file->getSize();
            $datos['tipo_archivo'] = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'imagen';
        }

        // Manejar campos booleanos y numéricos
        $datos['es_destacado'] = $request->boolean('es_destacado', false);
        $datos['esta_activo'] = $request->boolean('esta_activo', true);
        $datos['orden_visualizacion'] = $request->orden_visualizacion ?? 0;
        $datos['subido_por'] = auth()->id();

        GaleriaImagen::create($datos);

        return redirect()->route('admin.galeria.index')
            ->with('success', 'Archivo subido a la galería exitosamente.');
    }

    /**
     * Muestra el formulario para editar.
     */
    public function edit(GaleriaImagen $galeria)
    {
        return view('admin.galeria.edit', compact('galeria'));
    }

    /**
     * Actualiza una imagen/video.
     */
    public function update(StoreGaleriaImagenRequest $request, GaleriaImagen $galeria)
    {
        $datos = $request->validated();

        // Manejar nueva subida de archivo
        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior
            if ($galeria->ruta_archivo) {
                Storage::disk('public')->delete($galeria->ruta_archivo);
            }

            $file = $request->file('archivo');
            $path = $file->store('galeria', 'public');
            
            $datos['ruta_archivo'] = $path;
            $datos['tipo_mime'] = $file->getMimeType();
            $datos['tamano_archivo'] = $file->getSize();
            $datos['tipo_archivo'] = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'imagen';
        }

        // Manejar campos booleanos y numéricos
        $datos['es_destacado'] = $request->boolean('es_destacado', false);
        $datos['esta_activo'] = $request->boolean('esta_activo', true);
        $datos['orden_visualizacion'] = $request->orden_visualizacion ?? 0;
        $datos['actualizado_por'] = auth()->id();

        $galeria->update($datos);

        return redirect()->route('admin.galeria.index')
            ->with('success', 'Archivo actualizado exitosamente.');
    }

    /**
     * Elimina (desactiva) una imagen/video.
     */
    public function destroy(GaleriaImagen $galeria)
    {
        // Eliminar archivo físico
        if ($galeria->ruta_archivo) {
            Storage::disk('public')->delete($galeria->ruta_archivo);
        }

        $galeria->update([
            'esta_activo' => false,
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()->route('admin.galeria.index')
            ->with('success', 'Archivo eliminado de la galería.');
    }
}