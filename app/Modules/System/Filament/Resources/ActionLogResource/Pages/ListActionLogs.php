<?php

namespace App\Modules\System\Filament\Resources\ActionLogResource\Pages;

use App\Modules\System\Filament\Resources\ActionLogResource;
use Filament\Resources\Pages\ListRecords;

class ListActionLogs extends ListRecords
{
    protected static string $resource = ActionLogResource::class;
}
