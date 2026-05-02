<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель клиента.
 *
 * @property int $id
 * @property string $company_name Название компании
 * @property string $name ФИО контактного лица
 * @property int|null $manager_id ID менеджера, ведущего клиента
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User|null $manager Менеджер, закреплённый за клиентом
 */
class Client extends Model
{
    /**
     * Атрибуты, доступные для массового заполнения.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_name',
        'name',
        'manager_id',
    ];

    /**
     * Получить менеджера, закреплённого за клиентом.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
