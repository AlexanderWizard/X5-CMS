<?php

namespace App\Modules\Cms\Filament\Resources\PageResource\Pages;

use App\Modules\Cms\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('admin.cms.pages.action.add')),
        ];
    }
}
