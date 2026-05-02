<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Заполнить таблицу клиентов тестовыми данными.
     */
    public function run(): void
    {
        $clients = [
            ['company_name' => 'ООО Рога и копыта', 'name' => 'Бендер О.И.'],
            ['company_name' => 'ИП Петров Петр', 'name' => 'Петров Петр Петрович'],
            ['company_name' => 'ИП Иванов И.И.', 'name' => 'Иванов Иван Иванович'],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
