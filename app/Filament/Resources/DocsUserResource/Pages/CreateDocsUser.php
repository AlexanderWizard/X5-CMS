<?php

namespace App\Filament\Resources\DocsUserResource\Pages;

use App\Filament\Resources\DocsUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocsUser extends CreateRecord
{
    protected static string $resource = DocsUserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
