<?php

namespace App\Modules\Blog\Filament\Resources\ArticleResource\Pages;

use App\Modules\Blog\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('admin.blog.articles.action.add')),
        ];
    }
}
