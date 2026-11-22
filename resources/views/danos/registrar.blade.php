@extends('layouts.main')

@section('content')
    <div class="page-head">
        <h1>Registrar dano</h1>
        <span class="spacer"></span>
        <a href="{{ route('veiculos.index') }}" class="btn btn-light">Voltar</a>
    </div>

    <form method="POST" action="{{ route('danos.store') }}" class="form-grid">
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
            <label>Data do dano</label>
            <input type="date" name="data" value="{{ old('data', date('Y-m-d')) }}">
        </div>

        <div class="field">
            <label>Gravidade *</label>
            <select name="gravidade">
                <option value="">Selecione...</option>
                @foreach ($severities as $key => $label)
                    <option value="{{ $key }}" {{ old('gravidade') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="field field-wide">
            <label>Localização do dano *</label>
            <select name="localizacao">
                <option value="">Selecione...</option>
                @foreach (\App\Models\Dano::LOCALIZACOES as $key => $label)
                    <option value="{{ $key }}" {{ old('localizacao') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="field field-wide">
            <label>Descrição do dano *</label>
            <textarea name="descricao" rows="4" required>{{ old('descricao') }}</textarea>
        </div>

        <div class="field field-wide">
            <label>Observações</label>
            <textarea name="observacoes" rows="3">{{ old('observacoes') }}</textarea>
        </div>

        <div class="field field-wide">
            <button type="submit" class="btn btn-primary">Registrar dano</button>
        </div>
    </form>
@endsection
