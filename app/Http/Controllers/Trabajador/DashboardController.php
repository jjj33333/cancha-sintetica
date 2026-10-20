<?php
namespace App\Http\Controllers\Trabajador;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('trabajador.dashboard');
    }
}