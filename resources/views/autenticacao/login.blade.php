@extends('layouts.main')

@section('content')
    <div class="auth-box">
        <h1>Login</h1>

        <div class="card">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label>E-mail ou CNPJ</label>
                <input type="text" name="email" value="{{ old('email') }}" required autofocus>

                <label>Senha</label>
                <input type="password" name="password" required>

                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </form>

            <p>
                Ainda não tem uma conta? <a href="{{ route('register') }}">Cadastre-se</a>
            </p>
        </div>
    </div>
@endsection
