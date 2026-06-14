<?php

namespace App\Modules\Cms\Filament\Resources\TemplateResource\Pages;

use App\Modules\Cms\Filament\Resources\TemplateResource;
use App\Modules\Cms\Models\Template;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTemplate extends EditRecord
{
    protected static string $resource = TemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Системные и дефолтный шаблоны удалять нельзя
            Actions\DeleteAction::make()
                ->visible(fn (Template $record) => !$record->isProtected()),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
