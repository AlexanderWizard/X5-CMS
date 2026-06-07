<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $db = config('database.connections.mysql.database');
        $exists = DB::select(
            "SELECT COUNT(*) AS cnt FROM information_schema.columns WHERE table_schema = ? AND table_name = 'users' AND column_name = 'timezone'",
            [$db]
        );

        if (!$exists[0]->cnt) {
            DB::statement("ALTER TABLE `users` ADD COLUMN `timezone` VARCHAR(64) NOT NULL DEFAULT 'UTC' AFTER `locale`");
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `users` DROP COLUMN IF EXISTS `timezone`");
    }
};
