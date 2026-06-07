<?php

namespace App\Filament\Resources\DocsUserResource\Pages;

use App\Filament\Resources\DocsUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocsUsers extends ListRecords
{
    protected static string $resource = DocsUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('admin.users.action.add')),
        ];
    }
}
