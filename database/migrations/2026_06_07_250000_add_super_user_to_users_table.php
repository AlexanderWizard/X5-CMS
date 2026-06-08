<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $db = config('database.connections.mysql.database');
        $exists = DB::select(
            "SELECT COUNT(*) AS cnt FROM information_schema.columns WHERE table_schema = ? AND table_name = 'users' AND column_name = 'super_user'",
            [$db]
        );

        if (!$exists[0]->cnt) {
            DB::statement("ALTER TABLE `users` ADD COLUMN `super_user` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_active`");

            // Существующие пользователи раньше имели полный доступ (без ролей).
            // Помечаем их как super_user, чтобы не заблокировать после ввода прав.
            DB::statement("UPDATE `users` SET `super_user` = 1");
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `users` DROP COLUMN IF EXISTS `super_user`");
    }
};
