<?php

namespace App\Modules\Blog\Filament\Resources\TagResource\Pages;

use App\Modules\Blog\Filament\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('admin.blog.tags.action.add'))
                ->modalWidth(Width::Large),
        ];
    }
}
