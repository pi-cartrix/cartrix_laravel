<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AutenticacaoController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('autenticacao.login', [
            'title' => 'Login',
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Informe o e-mail ou CNPJ.',
            'password.required' => 'Informe a senha.',
        ]);

        $identifier = trim($credentials['email']);
        $cnpj = preg_replace('/\D/', '', $identifier);

        $user = $cnpj !== '' && strlen($cnpj) === 14
            ? Usuario::findByCnpj($cnpj)
            : Usuario::findByEmail(strtolower($identifier));

        if ($user === null || ! Hash::check($credentials['password'], (string) $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Credenciais inválidas.');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('success', 'Login realizado com sucesso.');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('autenticacao.registro', [
            'title' => 'Cadastro de usuário',
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $cnpj = preg_replace('/\D/', '', (string) $request->input('cnpj', ''));
        $email = strtolower(trim((string) $request->input('email', '')));
        $name = trim((string) $request->input('name', ''));

        $validator = Validator::make([
            'cnpj' => $cnpj,
            'email' => $email,
            'password' => (string) $request->input('password', ''),
            'password_confirm' => (string) $request->input('password_confirm', ''),
        ], [
            'cnpj' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'same:password_confirm'],
        ], [
            'cnpj.required' => 'O CNPJ é obrigatório.',
            'email.required' => 'Informe um e-mail válido.',
            'email.email' => 'Informe um e-mail válido.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'password.same' => 'A confirmação da senha não confere.',
        ]);

        if ($cnpj !== '' && strlen($cnpj) !== 14) {
            $validator->errors()->add('cnpj', 'O CNPJ deve ter 14 dígitos.');
        } elseif ($cnpj !== '' && Usuario::findByCnpj($cnpj) !== null) {
            $validator->errors()->add('cnpj', 'Já existe uma conta cadastrada com este CNPJ.');
        }

        if ($email !== '' && Usuario::findByEmail($email) !== null) {
            $validator->errors()->add('email', 'Já existe uma conta cadastrada com este e-mail.');
        }

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('name', 'cnpj', 'email'));
        }

        $user = Usuario::create([
            'cnpj' => $cnpj,
            'email' => $email,
            'name' => $name,
            'password' => Hash::make((string) $request->input('password')),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Conta criada com sucesso.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Sessão encerrada.');
    }
}
