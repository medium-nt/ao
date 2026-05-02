<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Модель статуса услуги.
 *
 * @property int $id
 * @property int $service_id ID услуги
 * @property string $title Название статуса
 * @property int $sort_order Порядок сортировки
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ServiceStatus extends Model
{
    /**
     * Атрибуты, доступные для массового заполнения.
     *
     * @var list<string>
     */
    protected $fillable = [
        'service_id',
        'title',
        'sort_order',
    ];

    /**
     * Получить услугу, которой принадлежит статус.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
