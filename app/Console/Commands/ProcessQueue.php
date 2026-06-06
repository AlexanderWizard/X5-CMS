<?php

namespace App\Console\Commands;

use App\Models\MessageQueue;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('queue:processed')]
#[Description('Обрабатывает очередь сообщений: выставляет is_processed=1 для необработанных записей (до 20 штук за раз)')]
class ProcessQueue extends Command
{
    public function handle(): int
    {
        $records = MessageQueue::where('is_processed', 0)
            ->limit(20)
            ->get();

        if ($records->isEmpty()) {
            $this->info('Нет записей для обработки.');
            return self::SUCCESS;
        }

        $ids = $records->pluck('id');

        MessageQueue::whereIn('id', $ids)->update(['is_processed' => 1]);

        $this->info("Обработано записей: {$ids->count()}. ID: {$ids->implode(', ')}");

        return self::SUCCESS;
    }
}
