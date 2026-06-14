<?php

namespace App\Modules\Cms\Filament\Resources\BlockResource\Pages;

use App\Modules\Cms\Filament\Resources\BlockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlocks extends ListRecords
{
    protected static string $resource = BlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('admin.cms.blocks.action.add')),
        ];
    }
}
