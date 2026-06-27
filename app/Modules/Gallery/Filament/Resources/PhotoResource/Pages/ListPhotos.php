<?php

namespace App\Modules\Gallery\Filament\Resources\PhotoResource\Pages;

use App\Modules\Gallery\Filament\Resources\PhotoResource;
use Filament\Resources\Pages\ListRecords;

class ListPhotos extends ListRecords
{
    protected static string $resource = PhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PhotoResource::uploadAction(),
        ];
    }
}
