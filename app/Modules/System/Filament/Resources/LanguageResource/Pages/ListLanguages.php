<?php

namespace App\Modules\System\Filament\Resources\LanguageResource\Pages;

use App\Modules\System\Filament\Resources\LanguageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListLanguages extends ListRecords
{
    protected static string $resource = LanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Создание — в модальном окне (create-страница не зарегистрирована)
            Actions\CreateAction::make()
                ->label(__('admin.languages.action.add'))
                ->modalWidth(Width::TwoExtraLarge),
        ];
    }
}
