<?php

namespace App\Modules\Gallery\Filament\Resources\AlbumResource\Pages;

use App\Modules\Gallery\Filament\Resources\AlbumResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListAlbums extends ListRecords
{
    protected static string $resource = AlbumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('admin.gallery.albums.action.add'))
                ->modalWidth(Width::TwoExtraLarge),
        ];
    }
}
