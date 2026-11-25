<?php

namespace Database\Seeders;

use App\Models\Dano;
use App\Models\Marca;
use App\Models\Revisao;
use App\Models\Usuario;
use App\Models\Veiculo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('mongodb')->getDatabase()->drop();
        $this->command?->info('  banco resetado.');

        $marcas = ['Chevrolet', 'Fiat', 'Honda', 'Hyundai', 'Renault', 'Toyota', 'Volkswagen', 'Jeep', 'BYD',
            'Nissan', 'Chery', 'Ford', 'GWM', 'Citroën', 'RAM', 'Mitsubishi', 'Peugeot', 'Audi', 'BMW', 'Mercedes-Benz',
            'Volvo', 'Porsche', 'Kia', 'Subaru', 'Suzuki', 'Hyundai'];

        foreach ($marcas as $marca) {
            Marca::create(['nome' => $marca]);
        }

        $this->command?->info('  marcas: '.count($marcas).' inseridas.');

        $demo = Usuario::create([
            'cnpj' => '11222333000181',
            'email' => 'demo@cartrix.com',
            'password' => Hash::make('demo1234'),
            'name' => 'Cartrix Demo',
        ]);

        $this->command?->info('  usuario: demo@cartrix.com / demo1234');

        $specs = [
            ['Toyota', 'Corolla XEi 2.0', 'ABC1D23', '9BWZZZ377VT004251', 45230, 'disponivel'],
            ['Fiat', 'Uno Way 1.0', 'XRT2E44', '9BG139BB6PD123456', 81200, 'em_manutencao'],
            ['Volkswagen', 'Golf GTI', 'PLK9A11', '3VWSX7B29JM456789', 62740, 'em_revisao'],
            ['Honda', 'Civic Touring', 'MNO8D55', '93HFC8480RZ401234', 18300, 'disponivel'],
            ['Renault', 'Duster 1.6', 'KJH3F66', '9BDBB2A0KF078901', 109500, 'danificado'],
            ['Chevrolet', 'Onix Premier', 'QWE1G77', '9BGFS44E1PC345678', 27450, 'indisponivel'],
        ];

        $vehicleIds = [];

        foreach ($specs as [$marca, $modelo, $placa, $chassi, $km, $status]) {
            $vehicle = Veiculo::create([
                'marca' => $marca,
                'modelo' => $modelo,
                'placa' => $placa,
                'chassi' => $chassi,
                'quilometragem' => $km,
                'status' => $status,
                'danos' => [],
                'user_id' => (string) $demo->_id,
            ]);

            $vehicleIds[] = $vehicle->_id;
        }

        $totalVeiculos = 100;
        $modelos = [
            'Hatch 1.0', 'Sedan 1.6', 'SUV 2.0', 'Picape 2.8', 'Compacto 1.0',
            'Crossover 1.5', 'Sedan Turbo', 'Hatch Sport', 'SUV 4x4', 'Minivan 1.8',
        ];
        $statusPool = array_keys(Veiculo::STATUSES);

        for ($i = count($specs); $i < $totalVeiculos; $i++) {
            $n = $i - count($specs);
            $marca = $marcas[$n % count($marcas)];
            $modelo = $modelos[$n % count($modelos)];
            $placa = $this->gerarPlaca($n);
            $chassi = sprintf('9BWZZZ377VT%06d', $n);
            $km = 5000 + (($n * 7919) % 120000);
            $status = $statusPool[$n % count($statusPool)];

            $vehicle = Veiculo::create([
                'marca' => $marca,
                'modelo' => $modelo,
                'placa' => $placa,
                'chassi' => $chassi,
                'quilometragem' => $km,
                'status' => $status,
                'danos' => [],
                'user_id' => (string) $demo->_id,
            ]);

            $vehicleIds[] = $vehicle->_id;
        }

        $this->command?->info('  veiculos: '.count($vehicleIds).' inseridos.');

        $revisoes = [
            [$vehicleIds[0], '2026-08-01', 45230, 'troca_oleo', 'Substituição de óleo do motor e filtro de ar.', 'Filtro de óleo, óleo 5W30 sintético', 320.50, 'Carlos Mecânico'],
            [$vehicleIds[0], '2026-07-05', 43800, 'preventiva', 'Revisão preventiva completa.', 'Pastilhas de freio, velas de ignição', 890.00, 'Oficina Central'],
            [$vehicleIds[1], '2026-08-12', 81200, 'corretiva', 'Troca do alternador após pane elétrica.', 'Alternador 90A', 1350.75, 'Auto Elétrica Silva'],
            [$vehicleIds[2], '2026-08-20', 62740, 'revisao_programada', 'Revisão de 60.000 km programada pela montadora.', 'Kit de correia dentada, bomba d\'água', 2140.00, 'Concessionária VW'],
            [$vehicleIds[4], '2026-07-28', 109500, 'troca_pneus', 'Troca dos quatro pneus e alinhamento.', '4× pneu 215/65R16, alinhamento', 1780.00, 'Pneu Center'],
        ];

        foreach ($revisoes as [$vid, $data, $km, $tipo, $desc, $pecas, $valor, $resp]) {
            Revisao::create([
                'vehicle_id' => $vid,
                'data_revisao' => $this->parseDate($data),
                'quilometragem' => $km,
                'tipo' => $tipo,
                'descricao' => $desc,
                'pecas_substituidas' => $pecas,
                'valor' => $valor,
                'responsavel' => $resp,
            ]);
        }

        $this->command?->info('  revisoes: '.count($revisoes).' inseridas.');

        $danos = [
            [$vehicleIds[1], '2026-08-18', 'media', 'lateral_direita', 'Arranhão profundo na porta do passageiro e quebra da maçaneta.', 'Aguardando orçamento da funilaria.'],
            [$vehicleIds[4], '2026-08-22', 'alta', 'dianteira', 'Para-choque dianteiro danificado após colisão.', 'Veículo fora de operação.'],
            [$vehicleIds[4], '2026-06-10', 'baixa', 'traseira', 'Pequeno amassado na tampa do porta-malas.', 'Sem risco estrutural.'],
            [$vehicleIds[5], '2026-08-25', 'critica', 'teto', 'Danos estruturais graves no teto.', 'Avaliar possibilidade de sucateamento.'],
        ];

        foreach ($danos as [$vid, $data, $grav, $loc, $desc, $obs]) {
            Dano::create([
                'vehicle_id' => $vid,
                'descricao' => $desc,
                'data' => $this->parseDate($data),
                'gravidade' => $grav,
                'localizacao' => $loc,
                'observacoes' => $obs,
            ]);
        }

        $this->command?->info('  danos: '.count($danos).' inseridos.');
        $this->command?->info('Concluido. Login: demo@cartrix.com / senha demo1234 (CNPJ 11.222.333/0001-81)');
    }

    private function gerarPlaca(int $n): string
    {
        $letras = range('A', 'Z');

        return 'G'
            .$letras[intdiv($n, 26) % 26]
            .$letras[$n % 26]
            .(intdiv($n, 7) % 10)
            .$letras[intdiv($n, 3) % 26]
            .(($n * 7) % 10)
            .(($n * 3) % 10);
    }

    private function parseDate(string $date): Carbon
    {
        return Carbon::createFromTimestamp((int) strtotime($date));
    }
}
