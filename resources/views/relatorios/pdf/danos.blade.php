@extends('relatorios.pdf.layout')

@section('title', 'Relatório de danos')
@section('heading', 'Relatório de danos')
@section('subtitle', 'Danos registrados na frota')

@section('content')
    <table class="summary">
        <tr>
            <td>
                <div class="label">Total de danos</div>
                <div class="value">{{ $damages->count() }}</div>
            </td>
        </tr>
    </table>

    @if ($damages->isEmpty())
        <div class="empty">Nenhum dano registrado.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Veículo</th>
                    <th>Gravidade</th>
                    <th>Localização</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($damages as $damage)
                    @php($vehicle = $vehicles[(string) $damage->vehicle_id] ?? null)
                    <tr>
                        <td>{{ $damage->data?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            @if ($vehicle !== null)
                                {{ $vehicle->placa }} — {{ $vehicle->marca }} {{ $vehicle->modelo }}
                            @else
                                Veículo não encontrado
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $damage->gravidade }}">
                                {{ $severities[$damage->gravidade] ?? $damage->gravidade }}
                            </span>
                        </td>
                        <td>{{ $locations[$damage->localizacao] ?? $damage->localizacao }}</td>
                        <td>
                            {{ $damage->descricao }}
                            @if ($damage->observacoes)
                                <br><span class="muted">{{ $damage->observacoes }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
