@extends('layouts.main')

@section('content')
    <div class="page-head">
        <h1>Registrar revisão</h1>
        <span class="spacer"></span>
        <a href="{{ route('veiculos.index') }}" class="btn btn-light">Voltar</a>
    </div>

    <form method="POST" action="{{ route('revisoes.store') }}" class="form-grid">
        @csrf

        <div class="field field-wide">
            <label>Veículo *</label>
            <select name="vehicle_id" required>
                <option value="">Selecione um veículo...</option>
                @foreach ($vehicles as $vehicle)
                    <option value="{{ (string) $vehicle->_id }}" {{ old('vehicle_id', $selectedVehicleId) === (string) $vehicle->_id ? 'selected' : '' }}>
                        {{ $vehicle->placa }} — {{ $vehicle->marca }} {{ $vehicle->modelo }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label>Data da revisão *</label>
            <input type="date" name="data_revisao" value="{{ old('data_revisao', date('Y-m-d')) }}" required>
        </div>

        <div class="field">
            <label>Quilometragem</label>
            <input type="number" name="quilometragem" min="0" step="1" value="{{ old('quilometragem', '0') }}">
        </div>

        <div class="field">
            <label>Tipo de revisão *</label>
            <select name="tipo">
                <option value="">Selecione...</option>
                @foreach ($types as $key => $label)
                    <option value="{{ $key }}" {{ old('tipo') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label>Valor (R$)</label>
            <input type="number" name="valor" min="0" step="0.01" value="{{ old('valor', '0') }}">
        </div>

        <div class="field field-wide">
            <label>Peças substituídas</label>
            <input type="text" name="pecas_substituidas" value="{{ old('pecas_substituidas') }}" placeholder="Ex.: pastilhas de freio, óleo 5W30...">
        </div>

        <div class="field">
            <label>Responsavel *</label>
            <input type="text" name="responsavel" value="{{ old('responsavel') }}" required>
        </div>

        <div class="field field-wide">
            <label>Descrição *</label>
            <textarea name="descricao" rows="4" required>{{ old('descricao') }}</textarea>
        </div>

        <div class="field field-wide">
            <button type="submit" class="btn btn-primary">Registrar revisão</button>
        </div>
    </form>
@endsection
