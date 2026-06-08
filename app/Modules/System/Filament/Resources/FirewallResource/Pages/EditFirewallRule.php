<?php

namespace App\Modules\System\Filament\Resources\FirewallResource\Pages;

use App\Modules\System\Filament\Resources\FirewallResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFirewallRule extends EditRecord
{
    protected static string $resource = FirewallResource::class;

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
