<?php

namespace App\Modules\Cms\Filament\Resources\PageResource\Pages;

use App\Modules\Cms\Filament\Resources\PageResource;
use App\Modules\Cms\Models\Page;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Главную страницу удалять нельзя
            Actions\DeleteAction::make()
                ->visible(fn (Page $record) => !$record->is_home),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
