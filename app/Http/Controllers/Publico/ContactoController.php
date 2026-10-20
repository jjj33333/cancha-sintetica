<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\InformacionContacto;

class ContactoController extends Controller
{
    public function index()
    {
        $contacto = InformacionContacto::activa()->first();

        return view('publico.contacto', compact('contacto'));
    }
}