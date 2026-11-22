@extends('relatorios.pdf.layout')

@section('title', 'Relatório de revisões')
@section('heading', 'Relatório de revisões')
@section('subtitle', 'Histórico consolidado de revisões da frota')

@section('content')
    <table class="summary">
        <tr>
            <td>
                <div class="label">Total de revisões</div>
                <div class="value">{{ $revisions->count() }}</div>
            </td>
            <td>
                <div class="label">Valor total</div>
                <div class="value">R$ {{ number_format((float) $total, 2, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    @if ($revisions->isEmpty())
        <div class="empty">Nenhuma revisão registrada.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Veículo</th>
                    <th>Tipo</th>
                    <th>Quilometragem</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Responsável</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($revisions as $revision)
                    @php($vehicle = $vehicles[(string) $revision->vehicle_id] ?? null)
                    <tr>
                        <td>{{ $revision->data_revisao?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            @if ($vehicle !== null)
                                {{ $vehicle->placa }} — {{ $vehicle->marca }} {{ $vehicle->modelo }}
                            @else
                                Veículo não encontrado
                            @endif
                        </td>
                        <td>{{ $types[$revision->tipo] ?? $revision->tipo }}</td>
                        <td>{{ number_format((int) $revision->quilometragem, 0, ',', '.') }} km</td>
                        <td>
                            {{ $revision->descricao }}
                            @if ($revision->pecas_substituidas)
                                <br><span class="muted">Peças: {{ $revision->pecas_substituidas }}</span>
                            @endif
                        </td>
                        <td>R$ {{ number_format((float) $revision->valor, 2, ',', '.') }}</td>
                        <td>{{ $revision->responsavel }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">Total investido em revisões</td>
                    <td colspan="2">R$ {{ number_format((float) $total, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    @endif
@endsection
