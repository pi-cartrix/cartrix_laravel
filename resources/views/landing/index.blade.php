@extends('layouts.main')

@section('content')
    <section class="landing-hero">
        <h1>Sua frota. Sob controle.</h1>
        <p class="muted">
            Cadastre seus veículos, acompanhe revisões e danos e gere relatórios completos
            em um só lugar. Cada conta tem sua própria frota, com acesso separado e seguro.
        </p>
        <div class="landing-actions">
            <a href="{{ route('register') }}" class="btn btn-primary">Criar conta</a>
            <a href="{{ route('login') }}" class="btn btn-outline">Já tenho conta</a>
        </div>
    </section>

    <section class="landing-section">
        <h2>Plano simples, por veículo</h2>
        <div class="card-grid">
            <div class="card">
                <h3>Preço</h3>
                <p class="price-highlight">
                    R$ {{ number_format($pricePerVehicle, 2, ',', '.') }}
                    <span class="muted">por veículo / mês</span>
                </p>
                <p class="muted">Sem taxa de adesão. Você paga apenas pelos veículos cadastrados na sua frota.</p>
            </div>
            <div class="card">
                <h3>Incluído no plano</h3>
                <ul class="feature-list">
                    <li>Cadastro e acompanhamento da frota</li>
                    <li>Histórico de revisões e danos por veículo</li>
                    <li>Relatórios completos em PDF</li>
                </ul>
            </div>
        </div>
    </section>
@endsection
