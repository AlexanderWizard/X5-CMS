<?php

namespace App\Modules\Cms\Filament\Resources\MenuItemResource\Pages;

use App\Modules\Cms\Filament\Resources\MenuItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListMenuItems extends ListRecords
{
    protected static string $resource = MenuItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('admin.cms.menu.action.add'))
                ->modalWidth(Width::Large),
        ];
    }
}
