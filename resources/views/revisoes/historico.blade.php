@extends('layouts.main')

@section('content')
    <div class="page-head">
        <h1>Histórico de revisões</h1>
        <span class="spacer"></span>
        <a href="{{ route('veiculos.index') }}" class="btn btn-light">Voltar</a>
    </div>

    <div class="card">
        <h3>
            {{ $vehicle->marca }} {{ $vehicle->modelo }}
            <span class="muted">— placa {{ $vehicle->placa }}</span>
        </h3>

        @if (! empty($vehicle->quilometragem))
            <p>Quilometragem atual: {{ number_format((int) $vehicle->quilometragem, 0, ',', '.') }} km</p>
        @endif
    </div>

    @if ($maintenances->isEmpty())
        <div class="empty-state">
            <p>Nenhuma revisão registrada para este veículo.</p>
            <a href="{{ route('revisoes.create', ['vehicle_id' => (string) $vehicle->_id]) }}" class="btn btn-primary">Registrar primeira revisão</a>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>Quilometragem</th>
                        <th>Descrição</th>
                        <th>Peças</th>
                        <th>Valor</th>
                        <th>Responsavel</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($maintenances as $maintenance)
                        <tr>
                            <td>{{ $maintenance->data_revisao?->format('d/m/Y') ?? '—' }}</td>
                            <td>{{ \App\Models\Revisao::TIPOS[$maintenance->tipo] ?? $maintenance->tipo }}</td>
                            <td>{{ number_format((int) $maintenance->quilometragem, 0, ',', '.') }} km</td>
                            <td>{{ $maintenance->descricao }}</td>
                            <td>{{ $maintenance->pecas_substituidas }}</td>
                            <td>R$ {{ number_format((float) $maintenance->valor, 2, ',', '.') }}</td>
                            <td>{{ $maintenance->responsavel }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5"><strong>Total investido em revisões</strong></td>
                        <td colspan="2"><strong>R$ {{ number_format((float) $total, 2, ',', '.') }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
@endsection
