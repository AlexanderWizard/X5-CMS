<?php

namespace App\Modules\System\Filament\Resources\RoleResource\Pages;

use App\Modules\System\Filament\Resources\RoleResource;
use App\Modules\System\Filament\Resources\RoleResource\Concerns\HandlesPermissions;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    use HandlesPermissions;

    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
