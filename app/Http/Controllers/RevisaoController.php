<?php

namespace App\Http\Controllers;

use App\Models\Revisao;
use App\Models\Veiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RevisaoController extends Controller
{
    public function history(string $id): View|RedirectResponse
    {
        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            return redirect()->route('veiculos.index')
                ->with('error', 'Veículo não encontrado.');
        }

        $maintenances = Revisao::porVeiculo($id);
        $total = 0.0;

        foreach ($maintenances as $maintenance) {
            $total += (float) $maintenance->valor;
        }

        return view('revisoes.historico', [
            'title' => 'Histórico de revisões',
            'vehicle' => $vehicle,
            'maintenances' => $maintenances,
            'total' => $total,
        ]);
    }

    public function create(Request $request): View
    {
        return view('revisoes.registrar', [
            'title' => 'Registrar revisão',
            'vehicles' => Veiculo::all(),
            'types' => Revisao::TIPOS,
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

        $date = $this->parseDate((string) $request->input('data_revisao', ''));

        $data = [
            'vehicle_id' => $vehicle->_id,
            'data_revisao' => $date,
            'quilometragem' => (int) $request->input('quilometragem', 0),
            'tipo' => trim((string) $request->input('tipo', '')),
            'descricao' => trim((string) $request->input('descricao', '')),
            'pecas_substituidas' => trim((string) $request->input('pecas_substituidas', '')),
            'valor' => (float) str_replace(',', '.', (string) $request->input('valor', '0')),
            'responsavel' => trim((string) $request->input('responsavel', '')),
        ];

        $validator = Validator::make($data, [
            'data_revisao' => ['required'],
            'tipo' => ['required', 'string', Rule::in(array_keys(Revisao::TIPOS))],
            'descricao' => ['required', 'string'],
            'responsavel' => ['required', 'string', 'max:255'],
            'quilometragem' => ['required', 'integer', 'min:0'],
            'valor' => ['required', 'numeric', 'min:0'],
        ], [
            'data_revisao.required' => 'Informe uma data válida.',
            'tipo.required' => 'Selecione o tipo de revisão.',
            'tipo.in' => 'Selecione o tipo de revisão.',
            'descricao.required' => 'Descreva a revisão realizada.',
            'responsavel.required' => 'Informe o responsável.',
            'quilometragem.integer' => 'A quilometragem deve ser um número inteiro.',
            'quilometragem.min' => 'A quilometragem não pode ser negativa.',
            'valor.numeric' => 'Informe um valor válido.',
            'valor.min' => 'O valor não pode ser negativo.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Revisao::create($data);

        if ((int) $vehicle->quilometragem < $data['quilometragem']) {
            $vehicle->update(['quilometragem' => $data['quilometragem']]);
        }

        return redirect()->route('revisoes.history', (string) $vehicle->_id)
            ->with('success', 'Revisão registrada com sucesso.');
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
