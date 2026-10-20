<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInformacionContactoRequest;
use App\Models\InformacionContacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactoController extends Controller
{
    /**
     * Muestra el formulario de edición de información de contacto.
     * Solo existe un registro activo a la vez.
     */
    public function index()
    {
        // Obtener el registro activo o crear uno nuevo en memoria
        $contacto = InformacionContacto::activa()->first();
        
        if (!$contacto) {
            $contacto = new InformacionContacto([
                'pais' => 'Guatemala',
                'esta_activo' => true,
            ]);
        }

        return view('admin.contacto.index', compact('contacto'));
    }

    /**
     * Almacena o actualiza la información de contacto.
     */
    public function update(StoreInformacionContactoRequest $request)
    {
        $datos = $request->validated();

        // Buscar el registro existente o crear uno nuevo
        $contacto = InformacionContacto::activa()->first();
        
        if (!$contacto) {
            $contacto = new InformacionContacto();
            $datos['esta_activo'] = true;
        }

        // Manejar campos booleanos
        $datos['esta_activo'] = $request->boolean('esta_activo', true);

        // Manejar subida de logo
        if ($request->hasFile('logo')) {
            // Eliminar logo anterior si existe
            if ($contacto->logo) {
                Storage::disk('public')->delete($contacto->logo);
            }
            $datos['logo'] = $request->file('logo')->store('contacto', 'public');
        }

        // Manejar subida de favicon
        if ($request->hasFile('favicon')) {
            // Eliminar favicon anterior si existe
            if ($contacto->favicon) {
                Storage::disk('public')->delete($contacto->favicon);
            }
            $datos['favicon'] = $request->file('favicon')->store('contacto', 'public');
        }

        $datos['actualizado_por'] = auth()->id();

        $contacto->fill($datos);
        $contacto->save();

        return redirect()->route('admin.contacto.index')
            ->with('success', 'Información de contacto actualizada exitosamente.');
    }

    /**
     * Elimina el logo actual.
     */
    public function eliminarLogo()
    {
        $contacto = InformacionContacto::activa()->first();
        
        if ($contacto && $contacto->logo) {
            Storage::disk('public')->delete($contacto->logo);
            $contacto->update(['logo' => null]);
            
            return back()->with('success', 'Logo eliminado exitosamente.');
        }

        return back()->with('error', 'No se encontró el logo.');
    }

    /**
     * Elimina el favicon actual.
     */
    public function eliminarFavicon()
    {
        $contacto = InformacionContacto::activa()->first();
        
        if ($contacto && $contacto->favicon) {
            Storage::disk('public')->delete($contacto->favicon);
            $contacto->update(['favicon' => null]);
            
            return back()->with('success', 'Favicon eliminado exitosamente.');
        }

        return back()->with('error', 'No se encontró el favicon.');
    }
}