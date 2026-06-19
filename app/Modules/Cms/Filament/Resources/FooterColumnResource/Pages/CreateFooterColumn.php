<?php

namespace App\Modules\Cms\Filament\Resources\FooterColumnResource\Pages;

use App\Modules\Cms\Filament\Resources\FooterColumnResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFooterColumn extends CreateRecord
{
    protected static string $resource = FooterColumnResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
