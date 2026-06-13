<?php

namespace App\Modules\Cms\Filament\Resources\TemplateResource\Pages;

use App\Modules\Cms\Filament\Resources\TemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTemplates extends ListRecords
{
    protected static string $resource = TemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('admin.cms.templates.action.add')),
        ];
    }
}
