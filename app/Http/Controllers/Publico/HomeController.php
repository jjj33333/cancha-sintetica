<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Instalacion;
use App\Models\InformacionContacto;

class HomeController extends Controller
{
    /**
     * Muestra la página de inicio pública.
     */
    public function index()
    {
        // Obtenemos la primera instalación activa
        $instalacion = Instalacion::activas()->first();
        
        // Obtenemos la información de contacto activa
        $contacto = InformacionContacto::activa()->first();

        return view('publico.home', compact('instalacion', 'contacto'));
    }
}