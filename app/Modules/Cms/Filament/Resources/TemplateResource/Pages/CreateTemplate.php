<?php

namespace App\Modules\Cms\Filament\Resources\TemplateResource\Pages;

use App\Modules\Cms\Filament\Resources\TemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTemplate extends CreateRecord
{
    protected static string $resource = TemplateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
