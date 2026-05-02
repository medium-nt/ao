<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Модель клиента.
 *
 * @property int $id
 * @property string $company_name Название компании
 * @property string $name ФИО контактного лица
 * @property int|null $manager_id ID менеджера, ведущего клиента
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User|null $manager Менеджер, закреплённый за клиентом
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

    /**
     * Получить документы клиента.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(ClientDocument::class);
    }
}
