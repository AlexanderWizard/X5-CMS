<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $db = config('database.connections.mysql.database');

        $columns = [
            'meta_title'       => "VARCHAR(191) NULL DEFAULT NULL",
            'meta_keywords'    => "VARCHAR(255) NULL DEFAULT NULL",
            'meta_description' => "VARCHAR(500) NULL DEFAULT NULL",
        ];

        foreach ($columns as $name => $type) {
            $exists = DB::select(
                "SELECT COUNT(*) AS cnt FROM information_schema.columns WHERE table_schema = ? AND table_name = 'pages' AND column_name = ?",
                [$db, $name]
            );

            if (!$exists[0]->cnt) {
                DB::statement("ALTER TABLE `pages` ADD COLUMN `{$name}` {$type} AFTER `content`");
            }
        }
    }

    public function down(): void
    {
        foreach (['meta_title', 'meta_keywords', 'meta_description'] as $name) {
            DB::statement("ALTER TABLE `pages` DROP COLUMN IF EXISTS `{$name}`");
        }
    }
};
