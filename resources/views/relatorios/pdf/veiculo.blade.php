@extends('relatorios.pdf.layout')

@section('title', 'Ficha do veículo')
@section('heading', 'Ficha do veículo')
@section('subtitle', $vehicle->marca.' '.$vehicle->modelo.' · '.$vehicle->placa)

@section('content')
    <table class="summary">
        <tr>
            <td>
                <div class="label">Placa</div>
                <div class="value">{{ $vehicle->placa }}</div>
            </td>
            <td>
                <div class="label">Quilometragem</div>
                <div class="value">{{ number_format((int) $vehicle->quilometragem, 0, ',', '.') }} km</div>
            </td>
            <td>
                <div class="label">Status</div>
                <div class="value">{{ $statuses[$vehicle->status] ?? $vehicle->status }}</div>
            </td>
        </tr>
    </table>

    <table class="fields">
        <tr>
            <th>Marca</th>
            <td>{{ $vehicle->marca }}</td>
            <th>Modelo</th>
            <td>{{ $vehicle->modelo }}</td>
        </tr>
        <tr>
            <th>Chassi</th>
            <td colspan="3">{{ $vehicle->chassi }}</td>
        </tr>
    </table>

    <h2>Revisões</h2>

    @if ($revisions->isEmpty())
        <div class="empty">Nenhuma revisão registrada.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Quilometragem</th>
                    <th>Responsável</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($revisions as $revision)
                    <tr>
                        <td>{{ $revision->data_revisao?->format('d/m/Y') ?? '—' }}</td>
                        <td>{{ $types[$revision->tipo] ?? $revision->tipo }}</td>
                        <td>{{ number_format((int) $revision->quilometragem, 0, ',', '.') }} km</td>
                        <td>{{ $revision->responsavel }}</td>
                        <td>R$ {{ number_format((float) $revision->valor, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Danos</h2>

    @if ($damages->isEmpty())
        <div class="empty">Nenhum dano registrado.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Gravidade</th>
                    <th>Localização</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($damages as $damage)
                    <tr>
                        <td>{{ $damage->data?->format('d/m/Y') ?? '—' }}</td>
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
