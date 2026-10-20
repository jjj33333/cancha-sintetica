<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Anuncio;
use App\Models\InformacionContacto;

class AnunciosController extends Controller
{
    public function index()
    {
        $contacto = InformacionContacto::activa()->first();

        $anuncios = Anuncio::publicos()->get();

        return view('publico.anuncios', compact('contacto', 'anuncios'));
    }
}