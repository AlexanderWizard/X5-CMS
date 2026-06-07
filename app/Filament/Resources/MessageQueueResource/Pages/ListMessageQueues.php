<?php

namespace App\Filament\Resources\MessageQueueResource\Pages;

use App\Filament\Resources\MessageQueueResource;
use App\Filament\Widgets\QueueStatsWidget;
use Filament\Resources\Pages\ListRecords;

class ListMessageQueues extends ListRecords
{
    protected static string $resource = MessageQueueResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            QueueStatsWidget::class,
        ];
    }
}
