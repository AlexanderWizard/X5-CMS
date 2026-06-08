<?php

namespace App\Modules\System\Filament\Resources\FirewallResource\Pages;

use App\Modules\System\Filament\Resources\FirewallResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFirewallRules extends ListRecords
{
    protected static string $resource = FirewallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('admin.firewall.action.add')),
        ];
    }
}
