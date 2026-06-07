<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Rebuilds the `users` table to hold system users (formerly in `docs_users`).
 *
 * Steps:
 *  1. Drop the default Laravel `users` table (unused – no email-based auth).
 *  2. Rename `docs_users` → `users`.
 *  3. Add `locale` column if missing (may already exist from earlier migration).
 *  4. Remove columns that belonged only to the docs-user era (none extra here).
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop the default (unused) users table
        DB::statement('DROP TABLE IF EXISTS `users`');

        // 2. Rename docs_users → users
        //    (works even if docs_users doesn't exist yet — protected by IF EXISTS above)
        if ($this->tableExists('docs_users')) {
            DB::statement('RENAME TABLE `docs_users` TO `users`');
        } else {
            // Fresh install – create from scratch
            DB::statement("
                CREATE TABLE `users` (
                    `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `login`           VARCHAR(191)    NOT NULL,
                    `password`        VARCHAR(255)    NOT NULL,
                    `is_active`       TINYINT(1)      NOT NULL DEFAULT 1,
                    `failed_attempts` TINYINT         NOT NULL DEFAULT 0,
                    `locale`          VARCHAR(5)      NOT NULL DEFAULT 'ru',
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `users_login_unique` (`login`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }

        // 3. Ensure `locale` column exists (added in earlier migration on docs_users)
        if (!$this->columnExists('users', 'locale')) {
            DB::statement("ALTER TABLE `users` ADD COLUMN `locale` VARCHAR(5) NOT NULL DEFAULT 'ru' AFTER `failed_attempts`");
        }
    }

    public function down(): void
    {
        // Reverse: rename users → docs_users, recreate empty users table
        if ($this->tableExists('users')) {
            DB::statement('RENAME TABLE `users` TO `docs_users`');
        }

        DB::statement("
            CREATE TABLE IF NOT EXISTS `users` (
                `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `name`              VARCHAR(191)    NOT NULL,
                `email`             VARCHAR(191)    NOT NULL,
                `email_verified_at` TIMESTAMP       NULL,
                `password`          VARCHAR(255)    NOT NULL,
                `remember_token`    VARCHAR(100)    NULL,
                `created_at`        TIMESTAMP       NULL,
                `updated_at`        TIMESTAMP       NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `users_email_unique` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    private function tableExists(string $table): bool
    {
        $db = config('database.connections.mysql.database');
        $result = DB::select(
            "SELECT COUNT(*) AS cnt FROM information_schema.tables WHERE table_schema = ? AND table_name = ?",
            [$db, $table]
        );
        return $result[0]->cnt > 0;
    }

    private function columnExists(string $table, string $column): bool
    {
        $db = config('database.connections.mysql.database');
        $result = DB::select(
            "SELECT COUNT(*) AS cnt FROM information_schema.columns WHERE table_schema = ? AND table_name = ? AND column_name = ?",
            [$db, $table, $column]
        );
        return $result[0]->cnt > 0;
    }
};
