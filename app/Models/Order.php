<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Модель заказа (услуги клиента).
 *
 * @property int $id
 * @property int $client_id ID клиента
 * @property int $service_id ID услуги
 * @property int|null $status_id ID текущего статуса услуги
 * @property Carbon $start_date Дата начала
 * @property Carbon|null $end_date Дата завершения
 * @property string $price Стоимость
 * @property string|null $note Примечание
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Client $client
 * @property-read Service $service
 * @property-read ServiceStatus|null $status
 */
class Order extends Model
{
    protected $fillable = [
        'client_id',
        'service_id',
        'status_id',
        'start_date',
        'end_date',
        'price',
        'note',
    ];

    /**
     * Приведение типов атрибутов.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'price' => 'decimal:2',
        ];
    }

    /**
     * Клиент, которому принадлежит заказ.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Услуга, заказанная клиентом.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Текущий статус заказа в рамках услуги.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ServiceStatus::class, 'status_id');
    }
}
