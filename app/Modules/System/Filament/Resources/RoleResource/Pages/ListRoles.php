<?php

namespace App\Modules\System\Filament\Resources\RoleResource\Pages;

use App\Modules\System\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('admin.roles.action.add')),
        ];
    }
}
