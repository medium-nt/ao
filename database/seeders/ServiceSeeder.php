<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceStatus;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Заполнить таблицы услуг и статусов тестовыми данными.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Регистрация ООО/ИП',
                'description' => null,
                'statuses' => ['Договор', 'Сбор документов', 'Подача в налоговую', 'Получение результата', 'Готово'],
            ],
            [
                'title' => 'Упрощенная Ликвидация',
                'description' => null,
                'statuses' => ['Договор', 'Сбор документов', 'Подача документов', 'Публикация результата', 'Прочие действия', 'Готово'],
            ],
        ];

        foreach ($services as $serviceData) {
            $statuses = $serviceData['statuses'];
            unset($serviceData['statuses']);

            $service = Service::create($serviceData);

            foreach ($statuses as $order => $title) {
                ServiceStatus::create([
                    'service_id' => $service->id,
                    'title' => $title,
                    'sort_order' => $order + 1,
                ]);
            }
        }
    }
}
