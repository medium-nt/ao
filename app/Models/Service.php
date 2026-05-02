<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Модель услуги.
 *
 * @property int $id
 * @property string $title Название услуги
 * @property string|null $description Описание услуги
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<int, ServiceStatus> $statuses Статусы услуги
 */
class Service extends Model
{
    /**
     * Атрибуты, доступные для массового заполнения.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
    ];

    /**
     * Получить статусы услуги, отсортированные по порядку.
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(ServiceStatus::class)->orderBy('sort_order');
    }
}
