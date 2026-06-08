<?php

namespace App\Modules\System\Filament\Resources\FirewallResource\Pages;

use App\Modules\System\Filament\Resources\FirewallResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFirewallRule extends CreateRecord
{
    protected static string $resource = FirewallResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
