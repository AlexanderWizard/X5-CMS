<?php

namespace App\Modules\Cms\Filament\Resources\RedirectResource\Pages;

use App\Modules\Cms\Filament\Resources\RedirectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListRedirects extends ListRecords
{
    protected static string $resource = RedirectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->modalWidth(Width::TwoExtraLarge),
        ];
    }
}
