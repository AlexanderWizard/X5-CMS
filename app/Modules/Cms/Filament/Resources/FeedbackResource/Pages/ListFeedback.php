<?php

namespace App\Modules\Cms\Filament\Resources\FeedbackResource\Pages;

use App\Modules\Cms\Filament\Resources\FeedbackResource;
use Filament\Resources\Pages\ListRecords;

class ListFeedback extends ListRecords
{
    protected static string $resource = FeedbackResource::class;

    // Заявки создаются только с сайта — кнопки «Добавить» нет.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
