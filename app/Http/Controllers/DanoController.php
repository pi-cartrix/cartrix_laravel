<?php

namespace App\Http\Controllers;

use App\Models\Dano;
use App\Models\Veiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DanoController extends Controller
{
    public function index(string $id): View|RedirectResponse
    {
        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            return redirect()->route('veiculos.index')
                ->with('error', 'Veículo não encontrado.');
        }

        return view('danos.listar', [
            'title' => 'Danos do veículo',
            'vehicle' => $vehicle,
            'damages' => Dano::porVeiculo($id),
        ]);
    }

    public function create(Request $request): View
    {
        return view('danos.registrar', [
            'title' => 'Registrar dano',
            'vehicles' => Veiculo::all(),
            'severities' => Dano::GRAVIDADES,
            'selectedVehicleId' => (string) $request->query('vehicle_id', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $vehicle = Veiculo::find((string) $request->input('vehicle_id', ''));

        if ($vehicle === null) {
            return back()
                ->withInput()
                ->with('error', 'Selecione um veículo válido.');
        }

        $data = [
            'vehicle_id' => $vehicle->_id,
            'descricao' => trim((string) $request->input('descricao', '')),
            'data' => $this->parseDate((string) $request->input('data', '')),
            'gravidade' => trim((string) $request->input('gravidade', '')),
            'localizacao' => trim((string) $request->input('localizacao', '')),
            'observacoes' => trim((string) $request->input('observacoes', '')),
        ];

        $validator = Validator::make($data, [
            'descricao' => ['required', 'string'],
            'gravidade' => ['required', 'string', Rule::in(array_keys(Dano::GRAVIDADES))],
            'localizacao' => ['required', 'string', Rule::in(array_keys(Dano::LOCALIZACOES))],
        ], [
            'descricao.required' => 'Descreva o dano identificado.',
            'gravidade.required' => 'Selecione a gravidade do dano.',
            'gravidade.in' => 'Selecione a gravidade do dano.',
            'localizacao.required' => 'Informe a localização do dano.',
            'localizacao.in' => 'Informe a localização do dano.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Dano::create($data);

        if ((string) $vehicle->status !== 'danificado') {
            $vehicle->atualizarStatus('danificado');
        }

        return redirect()->route('danos.index', (string) $vehicle->_id)
            ->with('success', 'Dano registrado. O veículo foi marcado como danificado.');
    }

    private function parseDate(string $value): ?Carbon
    {
        if ($value === '') {
            return null;
        }

        $timestamp = strtotime($value);

        return $timestamp === false ? null : Carbon::createFromTimestamp($timestamp);
    }
}
