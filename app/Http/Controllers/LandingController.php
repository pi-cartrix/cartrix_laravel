<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('veiculos.index');
        }

        return view('landing.index', [
            'title' => 'Cartrix — Gestão de frotas',
            'pricePerVehicle' => Veiculo::PRECO_POR_VEICULO,
        ]);
    }
}
