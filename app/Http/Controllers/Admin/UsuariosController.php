<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUsuarioRequest;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    /**
     * Muestra la lista de usuarios.
     */
    public function index(Request $request)
    {
        $query = Usuario::orderByDesc('created_at');

        // Filtro por búsqueda (nombre o email)
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        // Filtro por rol
        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('esta_activo', $request->estado === 'activo');
        }

        $usuarios = $query->paginate(15);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Almacena un nuevo usuario.
     */
    public function store(StoreUsuarioRequest $request)
    {
        $datos = $request->validated();
        
        // Encriptar contraseña
        $datos['password'] = Hash::make($datos['password']);
        $datos['esta_activo'] = $request->boolean('esta_activo', true);

        Usuario::create($datos);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un usuario.
     */
    public function edit(Usuario $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Actualiza un usuario.
     */
    public function update(StoreUsuarioRequest $request, Usuario $usuario)
    {
        $datos = $request->validated();
        
        // Solo actualizar la contraseña si se proporcionó una nueva
        if (!empty($datos['password'])) {
            $datos['password'] = Hash::make($datos['password']);
        } else {
            unset($datos['password']);
        }

        $datos['esta_activo'] = $request->boolean('esta_activo', true);

        $usuario->update($datos);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Elimina (desactiva) un usuario.
     * Nota: Usamos "soft delete" lógico para no romper las relaciones de auditoría o reservas.
     */
    public function destroy(Usuario $usuario)
    {
        // Prevenir que un admin se desactive a sí mismo accidentalmente desde esta vista
        if ($usuario->id === auth()->id()) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No puedes desactivar tu propio usuario desde aquí.');
        }

        $usuario->update([
            'esta_activo' => false,
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario desactivado exitosamente.');
    }
}