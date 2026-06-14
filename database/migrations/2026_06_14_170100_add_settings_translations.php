<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Переводы раздела «Настройки сайта» (группа admin) в таблицу translations.
 * insertOrIgnore — безопасно на повторных/fresh-прогонах.
 */
return new class extends Migration
{
    public function up(): void
    {
        $rows = [
            // key => [ru, en]
            'settings.nav'   => ['Настройки сайта', 'Site settings'],
            'settings.title' => ['Настройки сайта', 'Site settings'],
            'settings.save'  => ['Сохранить', 'Save'],
            'settings.saved' => ['Настройки сохранены', 'Settings saved'],

            'settings.tab.general'     => ['Общие', 'General'],
            'settings.tab.seo'         => ['SEO', 'SEO'],
            'settings.tab.scripts'     => ['Скрипты и аналитика', 'Scripts & analytics'],
            'settings.tab.maintenance' => ['Обслуживание', 'Maintenance'],

            'settings.field.site_name'    => ['Название сайта', 'Site name'],
            'settings.field.site_tagline' => ['Слоган', 'Tagline'],
            'settings.field.site_logo'    => ['Логотип', 'Logo'],
            'settings.field.site_favicon' => ['Favicon', 'Favicon'],

            'settings.field.seo_title_suffix'      => ['Суффикс <title>', '<title> suffix'],
            'settings.field.seo_title_suffix_hint' => ['Добавляется к заголовку вкладки, напр. « — Моя компания»', 'Appended to the page title, e.g. " — My Company"'],
            'settings.field.seo_default_keywords'  => ['Ключевые слова по умолчанию', 'Default keywords'],
            'settings.field.seo_default_description' => ['Описание по умолчанию', 'Default description'],
            'settings.field.seo_index'      => ['Разрешить индексацию', 'Allow indexing'],
            'settings.field.seo_index_hint' => ['Выключите, чтобы закрыть сайт от поисковиков (noindex)', 'Turn off to hide the site from search engines (noindex)'],

            'settings.field.analytics_head'      => ['Код в <head>', 'Code in <head>'],
            'settings.field.analytics_head_hint' => ['Счётчики/верификации; вставляется в <head>', 'Counters/verifications; injected into <head>'],
            'settings.field.analytics_body'      => ['Код перед </body>', 'Code before </body>'],
            'settings.field.analytics_body_hint' => ['Скрипты аналитики; вставляется перед закрытием </body>', 'Analytics scripts; injected before closing </body>'],

            'settings.field.maintenance_mode'      => ['Режим обслуживания', 'Maintenance mode'],
            'settings.field.maintenance_mode_hint' => ['Сайт закрыт для посетителей; админы видят сайт как обычно', 'Site is closed for visitors; admins still see it normally'],
            'settings.field.maintenance_message'   => ['Сообщение на странице обслуживания', 'Maintenance page message'],
            'settings.field.contact_email'      => ['E-mail для уведомлений', 'Notification e-mail'],
            'settings.field.contact_email_hint' => ['Например, для заявок с форм', 'E.g. for form submissions'],
        ];

        $now = now();
        $insert = [];

        foreach ($rows as $key => [$ru, $en]) {
            $insert[] = ['group' => 'admin', 'key' => $key, 'locale' => 'ru', 'value' => $ru, 'created_at' => $now];
            $insert[] = ['group' => 'admin', 'key' => $key, 'locale' => 'en', 'value' => $en, 'created_at' => $now];
        }

        DB::table('translations')->insertOrIgnore($insert);
    }

    public function down(): void
    {
        DB::table('translations')
            ->where('group', 'admin')
            ->where('key', 'like', 'settings.%')
            ->delete();
    }
};
