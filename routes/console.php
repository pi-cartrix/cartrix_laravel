<?php

use App\Models\Usuario;
use App\Models\Veiculo;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('veiculos:atribuir-demo', function (): int {
    $demo = Usuario::where('email', 'demo@cartrix.com')->first();

    if ($demo === null) {
        $this->error('Usuario demo nao encontrado. Rode: php artisan db:seed');

        return Command::FAILURE;
    }

    $total = Veiculo::withoutGlobalScopes()
        ->whereNull('user_id')
        ->update(['user_id' => (string) $demo->_id]);

    $this->info("{$total} veiculo(s) sem proprietario atribuido(s) ao usuario demo.");

    return Command::SUCCESS;
})->purpose('Atribui ao usuario demo os veiculos antigos sem proprietario');
