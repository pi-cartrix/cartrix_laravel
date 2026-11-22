@extends('relatorios.pdf.layout')

@section('title', 'Relatório da frota')
@section('heading', 'Relatório da frota')
@section('subtitle', 'Visão geral dos veículos cadastrados')

@section('content')
    <table class="summary">
        <tr>
            <td>
                <div class="label">Total de veículos</div>
                <div class="value">{{ $vehicles->count() }}</div>
            </td>
            @foreach ($statusCounts as $status => $count)
                <td>
                    <div class="label">{{ $statuses[$status] ?? $status }}</div>
                    <div class="value">{{ $count }}</div>
                </td>
            @endforeach
        </tr>
    </table>

    @if ($vehicles->isEmpty())
        <div class="empty">Nenhum veículo cadastrado.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Marca / Modelo</th>
                    <th>Chassi</th>
                    <th>Quilometragem</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vehicles as $vehicle)
                    <tr>
                        <td>{{ $vehicle->placa }}</td>
                        <td>{{ $vehicle->marca }} {{ $vehicle->modelo }}</td>
                        <td>{{ $vehicle->chassi }}</td>
                        <td>{{ number_format((int) $vehicle->quilometragem, 0, ',', '.') }} km</td>
                        <td>
                            <span class="badge badge-{{ $vehicle->status }}">
                                {{ $statuses[$vehicle->status] ?? $vehicle->status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
