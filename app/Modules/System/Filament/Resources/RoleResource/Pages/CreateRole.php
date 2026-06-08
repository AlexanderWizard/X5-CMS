<?php

namespace App\Modules\System\Filament\Resources\RoleResource\Pages;

use App\Modules\System\Filament\Resources\RoleResource;
use App\Modules\System\Filament\Resources\RoleResource\Concerns\HandlesPermissions;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    use HandlesPermissions;

    protected static string $resource = RoleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
