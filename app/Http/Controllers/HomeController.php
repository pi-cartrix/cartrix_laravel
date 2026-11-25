<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));
        $vehicleCount = Veiculo::count();

        return view('home.index', [
            'title' => 'Frota de veículos',
            'vehicleCount' => $vehicleCount,
            'monthlyTotal' => $vehicleCount * Veiculo::PRECO_POR_VEICULO,
            'vehicles' => Veiculo::search($query, $status),
            'query' => $query,
            'status' => $status,
            'statuses' => Veiculo::STATUSES,
        ]);
    }
}
