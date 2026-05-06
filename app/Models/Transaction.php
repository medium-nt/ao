<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Модель финансовой транзакции заказа.
 *
 * @property int $id
 * @property int $order_id ID заказа
 * @property string $type Тип транзакции (prepayment/payment)
 * @property string $amount Сумма
 * @property string|null $note Комментарий
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Transaction extends Model
{
    public const TYPE_PREPAYMENT = 'prepayment';

    public const TYPE_PAYMENT = 'payment';

    /**
     * Атрибуты, доступные для массового заполнения.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_id',
        'type',
        'amount',
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
            'amount' => 'decimal:2',
        ];
    }

    /**
     * Получить заказ, которому принадлежит транзакция.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
