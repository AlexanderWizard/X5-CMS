<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $db = config('database.connections.mysql.database');
        $exists = DB::select(
            "SELECT COUNT(*) AS cnt FROM information_schema.columns WHERE table_schema = ? AND table_name = 'roles' AND column_name = 'permissions'",
            [$db]
        );

        if (!$exists[0]->cnt) {
            // MySQL 5.6 не имеет JSON-типа — храним как TEXT, Laravel кастит в array
            DB::statement("ALTER TABLE `roles` ADD COLUMN `permissions` TEXT NULL DEFAULT NULL AFTER `description`");
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `roles` DROP COLUMN IF EXISTS `permissions`");
    }
};
