@extends('layouts.main')

@section('content')
    <div class="page-head">
        <h1>Relatórios</h1>
    </div>

    <p class="muted">Selecione um relatório para gerar o PDF com os dados atuais da frota.</p>

    <div class="card-grid">
        <div class="card report-card">
            <h3>Frota completa</h3>
            <p class="muted">
                {{ $vehicleCount }} veículo(s) cadastrado(s). Inclui resumo por status e os dados de cada veículo.
            </p>
            <a href="{{ route('relatorios.frota') }}" class="btn btn-primary btn-block">Gerar PDF da frota</a>
        </div>

        <div class="card report-card">
            <h3>Revisões</h3>
            <p class="muted">
                {{ $revisionCount }} revisão(ões) registrada(s). Inclui veículo, tipo, quilometragem, descrição e valor.
            </p>
            <a href="{{ route('relatorios.revisoes') }}" class="btn btn-primary btn-block">Gerar PDF de revisões</a>
        </div>

        <div class="card report-card">
            <h3>Danos</h3>
            <p class="muted">
                {{ $damageCount }} dano(s) registrado(s). Inclui gravidade, localização e descrição.
            </p>
            <a href="{{ route('relatorios.danos') }}" class="btn btn-primary btn-block">Gerar PDF de danos</a>
        </div>

        <div class="card report-card">
            <h3>Ficha do veículo</h3>

            @if ($vehicles->isEmpty())
                <p class="muted">Nenhum veículo cadastrado para gerar a ficha.</p>
            @else
                <p class="muted">Gere a ficha completa de um veículo com suas revisões e danos.</p>

                <form method="GET" action="{{ route('relatorios.veiculo') }}">
                    <label for="vehicle_id">Veículo</label>
                    <select name="vehicle_id" id="vehicle_id" required>
                        <option value="">Selecione...</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ (string) $vehicle->_id }}">
                                {{ $vehicle->placa }} — {{ $vehicle->marca }} {{ $vehicle->modelo }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary btn-block">Gerar ficha em PDF</button>
                </form>
            @endif
        </div>
    </div>
@endsection
