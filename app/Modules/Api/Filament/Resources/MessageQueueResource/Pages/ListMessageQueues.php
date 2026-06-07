<?php

namespace App\Modules\Api\Filament\Resources\MessageQueueResource\Pages;

use App\Modules\Api\Filament\Resources\MessageQueueResource;
use App\Modules\Api\Filament\Widgets\QueueStatsWidget;
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
