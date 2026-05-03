<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Модель истории смены статусов заказа.
 *
 * Хранит snapshot названия статуса как строку (не FK),
 * чтобы удаление/переименование статуса не ломало историю.
 *
 * @property int $id
 * @property int $order_id ID заказа
 * @property string $status_title Название закрытого статуса (snapshot)
 * @property string|null $note Комментарий о причинах переноса
 * @property Carbon $created_at Дата закрытия этапа
 * @property Carbon $updated_at
 * @property-read Order $order
 */
class OrderHistory extends Model
{
    protected $fillable = [
        'order_id',
        'status_title',
        'note',
    ];

    /**
     * Заказ, к которому принадлежит запись истории.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
