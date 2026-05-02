<?php

namespace App\Enums;

/**
 * Типы документов клиента.
 */
enum DocumentType: string
{
    case Contract = 'contract';
    case CompanyCard = 'company_card';
    case Act = 'act';

    /**
     * Получить название типа документа на русском.
     */
    public function label(): string
    {
        return match ($this) {
            self::Contract => 'Договор',
            self::CompanyCard => 'Карточка предприятия',
            self::Act => 'Акт',
        };
    }
}
