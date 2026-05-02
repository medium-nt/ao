<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Модель услуги.
 *
 * @property int $id
 * @property string $title Название услуги
 * @property string|null $description Описание услуги
 * @property Carbon $created_at
 * @property Carbon $updated_at
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
}
