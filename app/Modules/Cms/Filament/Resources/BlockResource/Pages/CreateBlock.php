<?php

namespace App\Modules\Cms\Filament\Resources\BlockResource\Pages;

use App\Modules\Cms\Filament\Resources\BlockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlock extends CreateRecord
{
    protected static string $resource = BlockResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
