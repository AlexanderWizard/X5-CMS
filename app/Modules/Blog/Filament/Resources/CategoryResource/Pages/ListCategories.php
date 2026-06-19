<?php

namespace App\Modules\Blog\Filament\Resources\CategoryResource\Pages;

use App\Modules\Blog\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('admin.blog.categories.action.add'))
                ->modalWidth(Width::Large),
        ];
    }
}
