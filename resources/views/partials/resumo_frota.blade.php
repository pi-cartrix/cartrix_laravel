<div class="status-summary">
    <div class="stat-card">
        <span class="muted">Veículos cadastrados</span>
        <strong>{{ $vehicleCount }}</strong>
    </div>
    <div class="stat-card">
        <span class="muted">Mensalidade (R$ 9,90 por veículo)</span>
        <strong>R$ {{ number_format($monthlyTotal, 2, ',', '.') }}</strong>
    </div>
</div>
