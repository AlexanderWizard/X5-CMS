<?php

namespace App\Filament\Resources\DocsUserResource\Pages;

use App\Filament\Resources\DocsUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocsUser extends EditRecord
{
    protected static string $resource = DocsUserResource::class;

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
