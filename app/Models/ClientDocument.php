<?php

namespace App\Models;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Модель документа клиента.
 *
 * @property int $id
 * @property int $client_id ID клиента
 * @property DocumentType $type Тип документа
 * @property string $original_name Оригинальное имя файла
 * @property string $file_name Имя файла на диске
 * @property bool $is_approved Одобрён ли документ
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Client $client Клиент, которому принадлежит документ
 */
class ClientDocument extends Model
{
    /**
     * Атрибуты, доступные для массового заполнения.
     *
     * @var list<string>
     */
    protected $fillable = [
        'client_id',
        'type',
        'original_name',
        'file_name',
        'is_approved',
    ];

    /**
     * Приведение типов атрибутов.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => DocumentType::class,
            'is_approved' => 'boolean',
        ];
    }

    /**
     * Получить клиента, которому принадлежит документ.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Получить полный путь к файлу на диске.
     */
    public function filePath(): string
    {
        return "clients/{$this->client_id}/{$this->type->value}/{$this->file_name}";
    }
}
