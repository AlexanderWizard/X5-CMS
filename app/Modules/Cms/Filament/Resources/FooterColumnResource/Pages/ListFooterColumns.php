<?php

namespace App\Modules\Cms\Filament\Resources\FooterColumnResource\Pages;

use App\Modules\Cms\Filament\Resources\FooterColumnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFooterColumns extends ListRecords
{
    protected static string $resource = FooterColumnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('admin.cms.footer.action.add')),
        ];
    }
}
