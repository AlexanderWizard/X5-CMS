<?php

namespace App\Modules\System\Filament\Resources\UserResource\Pages;

use App\Modules\System\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('admin.users.action.add')),
        ];
    }
}
