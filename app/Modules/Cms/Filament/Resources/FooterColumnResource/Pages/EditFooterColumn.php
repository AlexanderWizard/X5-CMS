<?php

namespace App\Modules\Cms\Filament\Resources\FooterColumnResource\Pages;

use App\Modules\Cms\Filament\Resources\FooterColumnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFooterColumn extends EditRecord
{
    protected static string $resource = FooterColumnResource::class;

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
