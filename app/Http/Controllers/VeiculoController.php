<?php

namespace App\Http\Controllers;

use App\Models\Dano;
use App\Models\Marca;
use App\Models\Revisao;
use App\Models\Veiculo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VeiculoController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));

        $vehicles = Veiculo::search($query, $status);
        $statusCounts = array_fill_keys(array_keys(Veiculo::STATUSES), 0);

        foreach ($vehicles as $vehicle) {
            $key = (string) $vehicle->status;

            if (isset($statusCounts[$key])) {
                $statusCounts[$key]++;
            }
        }

        return view('veiculos.listar', [
            'title' => 'Frota de veículos',
            'vehicles' => $vehicles,
            'query' => $query,
            'status' => $status,
            'statuses' => Veiculo::STATUSES,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function create(): View
    {
        return view('veiculos.cadastrar', [
            'title' => 'Cadastrar veículo',
            'statuses' => Veiculo::STATUSES,
            'marcas' => Marca::getNames(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = [
            'marca' => trim((string) $request->input('marca', '')),
            'modelo' => trim((string) $request->input('modelo', '')),
            'placa' => strtoupper((string) preg_replace('/\s+/', '', (string) $request->input('placa', ''))),
            'chassi' => strtoupper((string) preg_replace('/\s+/', '', (string) $request->input('chassi', ''))),
            'quilometragem' => (int) $request->input('quilometragem', 0),
            'status' => trim((string) $request->input('status', 'disponivel')),
            'danos' => [],
        ];

        $validator = Validator::make($data, [
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'placa' => ['required', 'string', 'max:10'],
            'chassi' => ['required', 'string', 'min:11', 'max:255'],
            'quilometragem' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(array_keys(Veiculo::STATUSES))],
        ], [
            'marca.required' => 'A marca é obrigatória.',
            'modelo.required' => 'O modelo é obrigatório.',
            'placa.required' => 'A placa é obrigatória.',
            'chassi.required' => 'O chassi é obrigatório.',
            'chassi.min' => 'O chassi deve ter no mínimo 11 caracteres.',
            'quilometragem.integer' => 'A quilometragem deve ser um número inteiro.',
            'quilometragem.min' => 'A quilometragem não pode ser negativa.',
            'status.in' => 'Status inválido.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (Veiculo::findByPlate($data['placa']) !== null) {
            return back()
                ->withErrors(['placa' => 'Já existe um veículo cadastrado com esta placa.'])
                ->withInput();
        }

        Veiculo::create($data);

        return redirect()->route('veiculos.index')
            ->with('success', 'Veículo cadastrado com sucesso.');
    }

    public function show(string $id): View|RedirectResponse
    {
        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            return redirect()->route('veiculos.index')
                ->with('error', 'Veículo não encontrado.');
        }

        return view('veiculos.detalhes', [
            'title' => 'Detalhes do veículo',
            'vehicle' => $vehicle,
            'maintenances' => Revisao::porVeiculo($id),
            'damages' => Dano::porVeiculo($id),
            'statuses' => Veiculo::STATUSES,
            'danoLocalizacoes' => Dano::LOCALIZACOES,
        ]);
    }

    public function edit(string $id): View|RedirectResponse
    {
        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            return redirect()->route('veiculos.index')
                ->with('error', 'Veículo não encontrado.');
        }

        return view('veiculos.editar', [
            'title' => 'Editar veículo',
            'vehicle' => $vehicle,
            'statuses' => Veiculo::STATUSES,
            'marcas' => Marca::getNames(),
            'danoLocalizacoes' => Dano::LOCALIZACOES,
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            return redirect()->route('veiculos.index')
                ->with('error', 'Veículo não encontrado.');
        }

        $data = [
            'modelo' => trim((string) $request->input('modelo', '')),
            'placa' => strtoupper((string) preg_replace('/\s+/', '', (string) $request->input('placa', ''))),
            'quilometragem' => (int) $request->input('quilometragem', 0),
            'status' => trim((string) $request->input('status', 'disponivel')),
            'danos' => [],
        ];

        $validator = Validator::make($data, [
            'modelo' => ['required', 'string', 'max:255'],
            'placa' => ['required', 'string', 'max:10'],
            'quilometragem' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(array_keys(Veiculo::STATUSES))],
        ], [
            'modelo.required' => 'O modelo é obrigatório.',
            'placa.required' => 'A placa é obrigatória.',
            'quilometragem.integer' => 'A quilometragem deve ser um número inteiro.',
            'quilometragem.min' => 'A quilometragem não pode ser negativa.',
            'status.in' => 'Status inválido.',
        ]);

        $duplicate = Veiculo::findByPlate($data['placa']);

        if ($duplicate !== null && (string) $duplicate->_id !== $id) {
            $validator->errors()->add('placa', 'Já existe outro veículo com esta placa.');
        }

        if ($validator->fails()) {
            return redirect()->route('veiculos.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $vehicle->atualizarVeiculo($data);

        return redirect()->route('veiculos.index')
            ->with('success', 'Veículo atualizado com sucesso.');
    }

    public function updateStatus(Request $request, string $id): RedirectResponse
    {
        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            return redirect()->route('veiculos.index')
                ->with('error', 'Veículo não encontrado.');
        }

        $status = trim((string) $request->input('status', ''));

        if (! isset(Veiculo::STATUSES[$status])) {
            return redirect()->route('veiculos.index')
                ->with('error', 'Status inválido.');
        }

        $vehicle->atualizarStatus($status);

        return redirect()->route('veiculos.index')
            ->with('success', 'Status do veículo atualizado para "'.Veiculo::STATUSES[$status].'".');
    }

    public function destroy(string $id): RedirectResponse
    {
        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            return redirect()->route('veiculos.index')
                ->with('error', 'Veículo não encontrado.');
        }

        $vehicle->delete();

        return redirect()->route('veiculos.index')
            ->with('success', 'Veículo removido da frota.');
    }
}
