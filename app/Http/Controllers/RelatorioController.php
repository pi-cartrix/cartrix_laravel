<?php

namespace App\Http\Controllers;

use App\Models\Dano;
use App\Models\Revisao;
use App\Models\Veiculo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class RelatorioController extends Controller
{
    public function index(): View
    {
        $vehicles = Veiculo::orderBy('marca')->orderBy('modelo')->get();

        return view('relatorios.index', [
            'title' => 'Relatórios',
            'vehicles' => $vehicles,
            'vehicleCount' => $vehicles->count(),
            'revisionCount' => Revisao::count(),
            'damageCount' => Dano::count(),
        ]);
    }

    public function frota(): Response
    {
        $vehicles = Veiculo::orderBy('marca')->orderBy('modelo')->get();
        $statusCounts = array_fill_keys(array_keys(Veiculo::STATUSES), 0);

        foreach ($vehicles as $vehicle) {
            $status = (string) $vehicle->status;

            if (isset($statusCounts[$status])) {
                $statusCounts[$status]++;
            }
        }

        $pdf = Pdf::loadView('relatorios.pdf.frota', [
            'vehicles' => $vehicles,
            'statuses' => Veiculo::STATUSES,
            'statusCounts' => $statusCounts,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('relatorio-frota-'.now()->format('Ymd-His').'.pdf');
    }

    public function revisoes(): Response
    {
        $vehicles = Veiculo::all()->keyBy(fn (Veiculo $vehicle): string => (string) $vehicle->_id);
        $revisions = Revisao::orderByDesc('data_revisao')->get();
        $total = $revisions->sum(fn (Revisao $revision): float => (float) $revision->valor);

        $pdf = Pdf::loadView('relatorios.pdf.revisoes', [
            'vehicles' => $vehicles,
            'revisions' => $revisions,
            'types' => Revisao::TIPOS,
            'total' => $total,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('relatorio-revisoes-'.now()->format('Ymd-His').'.pdf');
    }

    public function danos(): Response
    {
        $vehicles = Veiculo::all()->keyBy(fn (Veiculo $vehicle): string => (string) $vehicle->_id);
        $damages = Dano::orderByDesc('data')->get();

        $pdf = Pdf::loadView('relatorios.pdf.danos', [
            'vehicles' => $vehicles,
            'damages' => $damages,
            'severities' => Dano::GRAVIDADES,
            'locations' => Dano::LOCALIZACOES,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('relatorio-danos-'.now()->format('Ymd-His').'.pdf');
    }

    public function veiculo(Request $request): Response
    {
        $id = trim((string) $request->query('vehicle_id', ''));
        $vehicle = preg_match('/^[a-f0-9]{24}$/i', $id) === 1 ? Veiculo::find($id) : null;

        if ($vehicle === null) {
            return redirect()->route('relatorios.index')
                ->with('error', 'Selecione um veículo válido para gerar o relatório.');
        }

        $pdf = Pdf::loadView('relatorios.pdf.veiculo', [
            'vehicle' => $vehicle,
            'revisions' => Revisao::porVeiculo($id),
            'damages' => Dano::porVeiculo($id),
            'statuses' => Veiculo::STATUSES,
            'types' => Revisao::TIPOS,
            'severities' => Dano::GRAVIDADES,
            'locations' => Dano::LOCALIZACOES,
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait');

        $plate = preg_replace('/[^A-Za-z0-9]/', '', (string) $vehicle->placa) ?: 'veiculo';

        return $pdf->download('relatorio-veiculo-'.$plate.'-'.now()->format('Ymd-His').'.pdf');
    }
}
