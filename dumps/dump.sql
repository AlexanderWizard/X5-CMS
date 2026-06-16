/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `action_logs` */

DROP TABLE IF EXISTS `action_logs`;

CREATE TABLE `action_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_login` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `properties` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `action_logs_user_id_index` (`user_id`),
  KEY `action_logs_event_index` (`event`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `action_logs` */

insert  into `action_logs`(`id`,`user_id`,`user_login`,`event`,`subject_type`,`subject_label`,`subject_id`,`properties`,`ip_address`,`created_at`) values 
(6,1,'admin','updated','User','Пользователи',2,'{\"changed\":[\"is_active\"]}','127.0.0.1','2026-06-09 19:29:35'),
(7,1,'admin','updated','User','Пользователи',2,'{\"changed\":[\"is_active\"]}','127.0.0.1','2026-06-09 19:33:54'),
(8,1,'admin','updated','User','Пользователи',1,'{\"changed\":[\"name\"]}','127.0.0.1','2026-06-09 21:11:45'),
(9,1,'admin','updated','User','Пользователи',2,'{\"changed\":[\"name\"]}','127.0.0.1','2026-06-09 21:11:53'),
(10,1,'admin','login','User','Users',1,NULL,'127.0.0.1','2026-06-13 18:37:15'),
(11,1,'admin','updated','Page','Страницы',1,'{\"changed\":[\"content\"]}','127.0.0.1','2026-06-13 18:48:40'),
(12,1,'admin','created','Page','Страницы',10,NULL,'127.0.0.1','2026-06-13 19:15:25'),
(13,1,'admin','updated','Page','Страницы',10,'{\"changed\":[\"template_id\"]}','127.0.0.1','2026-06-13 19:15:51'),
(14,1,'admin','updated','Page','Страницы',10,'{\"changed\":[\"template_id\"]}','127.0.0.1','2026-06-13 19:16:02'),
(15,1,'admin','login','User','Users',1,NULL,'127.0.0.1','2026-06-14 06:42:44'),
(16,1,'admin','updated','Page','Страницы',10,'{\"changed\":[\"content\"]}','127.0.0.1','2026-06-14 06:45:52'),
(17,1,'admin','created','Page','Страницы',13,NULL,'127.0.0.1','2026-06-14 06:56:43'),
(18,1,'admin','updated','Page','Страницы',13,'{\"changed\":[\"template_id\"]}','127.0.0.1','2026-06-14 06:59:43'),
(19,1,'admin','created','Template','Шаблоны',7,NULL,'127.0.0.1','2026-06-14 07:04:07'),
(20,1,'admin','deleted','Template','Шаблоны',7,NULL,'127.0.0.1','2026-06-14 07:04:22'),
(21,1,'admin','login','User','Users',1,NULL,'127.0.0.1','2026-06-14 07:06:08'),
(22,1,'admin','updated','Page','Страницы',1,'{\"changed\":[\"meta_description\",\"meta_keywords\",\"meta_title\"]}','127.0.0.1','2026-06-14 07:57:25'),
(23,1,'admin','updated','Template','Шаблоны',1,'{\"changed\":[\"body\"]}','127.0.0.1','2026-06-14 08:04:03'),
(24,1,'admin','login','User','Users',1,NULL,'127.0.0.1','2026-06-14 15:16:49'),
(25,1,'admin','login','User','Users',1,NULL,'127.0.0.1','2026-06-14 15:20:28'),
(26,1,'admin','updated','Translation','Translation',157,'{\"changed\":[\"value\"]}','127.0.0.1','2026-06-14 15:56:05'),
(27,1,'admin','updated','Translation','Переводы',156,'{\"changed\":[\"key\"]}','127.0.0.1','2026-06-14 17:20:33'),
(28,1,'admin','updated','Translation','Переводы',156,'{\"changed\":[\"key\"]}','127.0.0.1','2026-06-14 17:20:49'),
(29,1,'admin','updated','Translation','Переводы',157,'{\"changed\":[\"value\"]}','127.0.0.1','2026-06-14 17:20:58'),
(30,1,'admin','updated','FirewallRule','Файрвол',1,'{\"changed\":[\"description\"]}','127.0.0.1','2026-06-14 17:27:58'),
(31,1,'admin','deleted','FirewallRule','Файрвол',1,NULL,'127.0.0.1','2026-06-14 17:28:19'),
(32,1,'admin','login','User','Users',1,NULL,'127.0.0.1','2026-06-14 20:08:34'),
(33,1,'admin','login','User','Users',1,NULL,'127.0.0.1','2026-06-14 20:39:14'),
(34,1,'admin','login','User','Users',1,NULL,'127.0.0.1','2026-06-15 17:40:42'),
(35,1,'admin','login','User','Users',1,NULL,'127.0.0.1','2026-06-15 17:49:46'),
(36,1,'admin','login','User','Users',1,NULL,'127.0.0.1','2026-06-16 05:59:30'),
(37,1,'admin','updated','Page','Страницы',1,'{\"changed\":[\"i18n\"]}','127.0.0.1','2026-06-16 06:06:44'),
(38,1,'admin','updated','Page','Страницы',1,'{\"changed\":[\"content\",\"i18n\"]}','127.0.0.1','2026-06-16 06:07:11'),
(39,1,'admin','login','User','Users',1,NULL,'127.0.0.1','2026-06-16 17:41:50'),
(40,1,'admin','updated','Language','Language',2,'{\"changed\":[\"is_default\"]}','127.0.0.1','2026-06-16 17:46:57'),
(41,1,'admin','updated','Language','Language',1,'{\"changed\":[\"is_default\"]}','127.0.0.1','2026-06-16 17:47:26'),
(42,1,'admin','updated','Language','Language',2,'{\"changed\":[\"is_active\"]}','127.0.0.1','2026-06-16 17:47:38'),
(43,1,'admin','updated','Language','Language',2,'{\"changed\":[\"is_active\"]}','127.0.0.1','2026-06-16 17:47:50'),
(44,1,'admin','created','Page','Страницы',15,NULL,'127.0.0.1','2026-06-16 17:52:16'),
(45,1,'admin','updated','Page','Страницы',15,'{\"changed\":[\"parent_id\",\"content\",\"i18n\",\"meta_description\",\"meta_title\"]}','127.0.0.1','2026-06-16 17:52:23'),
(46,1,'admin','login','User','Users',1,NULL,'127.0.0.1','2026-06-16 18:09:09'),
(47,1,'admin','login','User','Users',1,NULL,'127.0.0.1','2026-06-16 18:09:31'),
(48,1,'admin','updated','User','Пользователи',1,'{\"changed\":[\"locale\"]}','127.0.0.1','2026-06-16 18:16:36'),
(49,1,'admin','updated','User','Users',1,'{\"changed\":[\"locale\"]}','127.0.0.1','2026-06-16 18:16:50'),
(50,1,'admin','created','Page','Страницы',16,NULL,'127.0.0.1','2026-06-16 19:16:22'),
(51,1,'admin','updated','Page','Страницы',16,'{\"changed\":[\"parent_id\"]}','127.0.0.1','2026-06-16 19:16:28');

/*Table structure for table `blocks` */

DROP TABLE IF EXISTS `blocks`;

CREATE TABLE `blocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `i18n` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blocks_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `blocks` */

insert  into `blocks`(`id`,`name`,`slug`,`value`,`i18n`,`created_at`) values 
(1,'Телефон','phone','+1 555 000-00-00','{\"en\":\"+1 555 000-00-00\",\"ru\":\"+7 900 000-00-00\"}','2026-06-14 07:59:58'),
(2,'E-mail','email','info@example.com','{\"en\":\"info@example.com\",\"ru\":\"info@example.com\"}','2026-06-14 07:59:58'),
(3,'Адрес','address','ул. Примерная, 1','{\"en\":\"ул. Примерная, 1\",\"ru\":\"ул. Примерная, 1\"}','2026-06-14 07:59:58'),
(4,'Главная · бейдж','home_hero_badge','an AI-built, AI-run CMS','{\"en\":\"an AI-built, AI-run CMS\",\"ru\":\"CMS, созданная и управляемая ИИ\"}','2026-06-16 06:04:07'),
(5,'Главная · заголовок 1','home_hero_title1','A website','{\"en\":\"A website\",\"ru\":\"Сайт, который\"}','2026-06-16 06:04:07'),
(6,'Главная · заголовок 2 (градиент)','home_hero_title2','built and run by AI','{\"en\":\"built and run by AI\",\"ru\":\"создаёт и ведёт ИИ\"}','2026-06-16 06:04:07'),
(7,'Главная · лид','home_hero_lead','Describe a page in plain words — AI assembles the structure, templates and content. Edit, translate and publish without code. The CMS core itself is written by AI.','{\"en\":\"Describe a page in plain words — AI assembles the structure, templates and content. Edit, translate and publish without code. The CMS core itself is written by AI.\",\"ru\":\"Опишите страницу словами — искусственный интеллект соберёт структуру, шаблоны и контент. Правьте, переводите и публикуйте без кода. Само ядро CMS тоже написано ИИ.\"}','2026-06-16 06:04:07'),
(8,'Главная · кнопка «Начать»','home_btn_start','Start with AI →','{\"en\":\"Start with AI →\",\"ru\":\"Начать с ИИ →\"}','2026-06-16 06:04:07'),
(9,'Главная · кнопка «Возможности»','home_btn_features','Features','{\"en\":\"Features\",\"ru\":\"Возможности\"}','2026-06-16 06:04:07'),
(10,'Главная · доверие 1','home_trust1','AI generation','{\"en\":\"AI generation\",\"ru\":\"AI-генерация\"}','2026-06-16 06:04:07'),
(11,'Главная · доверие 2','home_trust2','No code','{\"en\":\"No code\",\"ru\":\"Без кода\"}','2026-06-16 06:04:07'),
(12,'Главная · консоль · промпт','home_console_prompt','Create a “Pricing” page with three plans','{\"en\":\"Create a “Pricing” page with three plans\",\"ru\":\"Создай страницу «Тарифы» с тремя планами\"}','2026-06-16 06:04:07'),
(13,'Главная · консоль · строка 1','home_console1','Page structure ready','{\"en\":\"Page structure ready\",\"ru\":\"Структура страницы готова\"}','2026-06-16 06:04:07'),
(14,'Главная · консоль · строка 2','home_console2','pricing template generated','{\"en\":\"pricing template generated\",\"ru\":\"Шаблон pricing сгенерирован\"}','2026-06-16 06:04:07'),
(15,'Главная · консоль · строка 3','home_console3','ru · en translations added','{\"en\":\"ru · en translations added\",\"ru\":\"Переводы ru · en добавлены\"}','2026-06-16 06:04:07'),
(16,'Главная · консоль · строка 4','home_console4','Published','{\"en\":\"Published\",\"ru\":\"Опубликовано\"}','2026-06-16 06:04:07'),
(17,'Главная · метрика 1','home_stat1','page generation','{\"en\":\"page generation\",\"ru\":\"генерация страниц\"}','2026-06-16 06:04:07'),
(18,'Главная · метрика 2','home_stat2','lines of code by hand','{\"en\":\"lines of code by hand\",\"ru\":\"строк кода руками\"}','2026-06-16 06:04:07'),
(19,'Главная · метрика 3','home_stat3','ru · en out of the box','{\"en\":\"ru · en out of the box\",\"ru\":\"ru · en из коробки\"}','2026-06-16 06:04:07'),
(20,'Главная · метрика 4','home_stat4','built & run by AI','{\"en\":\"built & run by AI\",\"ru\":\"собрано и ведётся ИИ\"}','2026-06-16 06:04:07'),
(21,'Главная · возможности · надзаголовок','home_feat_eyebrow','Features','{\"en\":\"Features\",\"ru\":\"Возможности\"}','2026-06-16 06:04:07'),
(22,'Главная · возможности · заголовок','home_feat_h2','A CMS with AI inside','{\"en\":\"A CMS with AI inside\",\"ru\":\"CMS, в которой работает ИИ\"}','2026-06-16 06:04:07'),
(23,'Главная · возможности · подзаголовок','home_feat_sub','Build and grow your site by chatting — AI handles the routine.','{\"en\":\"Build and grow your site by chatting — AI handles the routine.\",\"ru\":\"Создавайте и развивайте сайт диалогом — рутину берёт на себя искусственный интеллект.\"}','2026-06-16 06:04:07'),
(24,'Главная · фича 1 · заголовок','home_f1_t','AI page generation','{\"en\":\"AI page generation\",\"ru\":\"AI-генерация страниц\"}','2026-06-16 06:04:07'),
(25,'Главная · фича 1 · текст','home_f1_d','Describe the task — AI creates the page, markup and copy.','{\"en\":\"Describe the task — AI creates the page, markup and copy.\",\"ru\":\"Опишите задачу словами — ИИ создаст страницу, разметку и тексты.\"}','2026-06-16 06:04:07'),
(26,'Главная · фича 2 · заголовок','home_f2_t','AI templates','{\"en\":\"AI templates\",\"ru\":\"AI-шаблоны\"}','2026-06-16 06:04:07'),
(27,'Главная · фича 2 · текст','home_f2_d','Output templates are generated and tweaked with prompts on the fly.','{\"en\":\"Output templates are generated and tweaked with prompts on the fly.\",\"ru\":\"Шаблоны вывода генерируются и правятся подсказками на лету.\"}','2026-06-16 06:04:07'),
(28,'Главная · фича 3 · заголовок','home_f3_t','No-code content','{\"en\":\"No-code content\",\"ru\":\"Контент без кода\"}','2026-06-16 06:04:07'),
(29,'Главная · фича 3 · текст','home_f3_d','Page tree, blocks and menus — visually from the panel, no developer.','{\"en\":\"Page tree, blocks and menus — visually from the panel, no developer.\",\"ru\":\"Дерево страниц, блоки и меню — визуально из панели, без разработчика.\"}','2026-06-16 06:04:07'),
(30,'Главная · фича 4 · заголовок','home_f4_t','Auto-translations','{\"en\":\"Auto-translations\",\"ru\":\"Авто-переводы\"}','2026-06-16 06:04:07'),
(31,'Главная · фича 4 · текст','home_f4_d','UI and content in ru/en; strings editable right in the admin.','{\"en\":\"UI and content in ru\\/en; strings editable right in the admin.\",\"ru\":\"Интерфейс и контент на ru\\/en; строки редактируются прямо в админке.\"}','2026-06-16 06:04:07'),
(32,'Главная · фича 5 · заголовок','home_f5_t','Roles & access','{\"en\":\"Roles & access\",\"ru\":\"Роли и доступ\"}','2026-06-16 06:04:07'),
(33,'Главная · фича 5 · текст','home_f5_d','Granular per-module permissions, IP firewall and full audit log.','{\"en\":\"Granular per-module permissions, IP firewall and full audit log.\",\"ru\":\"Гибкие права по модулям, файрвол по IP и полный аудит действий.\"}','2026-06-16 06:04:07'),
(34,'Главная · фича 6 · заголовок','home_f6_t','Action log','{\"en\":\"Action log\",\"ru\":\"Журнал действий\"}','2026-06-16 06:04:07'),
(35,'Главная · фича 6 · текст','home_f6_d','Every change — by humans and AI — is logged with event filters.','{\"en\":\"Every change — by humans and AI — is logged with event filters.\",\"ru\":\"Каждое изменение — человека и ИИ — фиксируется с фильтрами по событиям.\"}','2026-06-16 06:04:07'),
(36,'Главная · шаги · надзаголовок','home_steps_eyebrow','How it works','{\"en\":\"How it works\",\"ru\":\"Как это работает\"}','2026-06-16 06:04:07'),
(37,'Главная · шаги · заголовок','home_steps_h2','Three steps to a live site','{\"en\":\"Three steps to a live site\",\"ru\":\"Три шага — и сайт готов\"}','2026-06-16 06:04:07'),
(38,'Главная · шаги · подзаголовок','home_steps_sub','Describe the idea — AI assembles the rest.','{\"en\":\"Describe the idea — AI assembles the rest.\",\"ru\":\"Опишите идею — остальное соберёт искусственный интеллект.\"}','2026-06-16 06:04:07'),
(39,'Главная · шаг 1 · заголовок','home_s1_t','Describe it','{\"en\":\"Describe it\",\"ru\":\"Опишите словами\"}','2026-06-16 06:04:07'),
(40,'Главная · шаг 1 · текст','home_s1_d','“A services landing with pricing and a lead form” — in plain language.','{\"en\":\"“A services landing with pricing and a lead form” — in plain language.\",\"ru\":\"«Лендинг услуг с тарифами и формой заявки» — обычным языком.\"}','2026-06-16 06:04:07'),
(41,'Главная · шаг 2 · заголовок','home_s2_t','AI assembles','{\"en\":\"AI assembles\",\"ru\":\"ИИ собирает\"}','2026-06-16 06:04:07'),
(42,'Главная · шаг 2 · текст','home_s2_d','Pages, templates, blocks and translations are created automatically.','{\"en\":\"Pages, templates, blocks and translations are created automatically.\",\"ru\":\"Страницы, шаблоны, блоки и переводы создаются автоматически.\"}','2026-06-16 06:04:07'),
(43,'Главная · шаг 3 · заголовок','home_s3_t','Publish','{\"en\":\"Publish\",\"ru\":\"Публикуете\"}','2026-06-16 06:04:07'),
(44,'Главная · шаг 3 · текст','home_s3_d','Tweak details in the panel and hit Publish. Done.','{\"en\":\"Tweak details in the panel and hit Publish. Done.\",\"ru\":\"Правите мелочи в панели и жмёте «Опубликовать». Готово.\"}','2026-06-16 06:04:07'),
(45,'Главная · отзывы · надзаголовок','home_rev_eyebrow','Reviews','{\"en\":\"Reviews\",\"ru\":\"Отзывы\"}','2026-06-16 06:04:07'),
(46,'Главная · отзывы · заголовок','home_rev_h2','Sites launched in an evening','{\"en\":\"Sites launched in an evening\",\"ru\":\"Запускают сайты за вечер\"}','2026-06-16 06:04:07'),
(47,'Главная · отзывы · подзаголовок','home_rev_sub','Teams build and run sites by chatting with AI — no queue to a developer.','{\"en\":\"Teams build and run sites by chatting with AI — no queue to a developer.\",\"ru\":\"Команды собирают и ведут сайты диалогом с ИИ — без очереди к разработчику.\"}','2026-06-16 06:04:07'),
(48,'Главная · отзыв 1 · цитата','home_r1_q','“Built a landing and a blog in one evening — just described what I needed. AI made the pages, templates and translations.”','{\"en\":\"“Built a landing and a blog in one evening — just described what I needed. AI made the pages, templates and translations.”\",\"ru\":\"«Собрал лендинг и блог за один вечер — просто описал, что нужно. ИИ сделал страницы, шаблоны и переводы.»\"}','2026-06-16 06:04:07'),
(49,'Главная · отзыв 1 · имя','home_r1_n','Alexey Morozov','{\"en\":\"Alexey Morozov\",\"ru\":\"Алексей Морозов\"}','2026-06-16 06:04:07'),
(50,'Главная · отзыв 1 · роль','home_r1_r','studio founder','{\"en\":\"studio founder\",\"ru\":\"основатель студии\"}','2026-06-16 06:04:07'),
(51,'Главная · отзыв 2 · цитата','home_r2_q','“Migrated our corporate site without a developer. Content is now edited by a content manager right in the panel.”','{\"en\":\"“Migrated our corporate site without a developer. Content is now edited by a content manager right in the panel.”\",\"ru\":\"«Перенесли корпоративный сайт без программиста. Контент теперь правит контент-менеджер прямо в панели.»\"}','2026-06-16 06:04:07'),
(52,'Главная · отзыв 2 · имя','home_r2_n','Irina Kovaleva','{\"en\":\"Irina Kovaleva\",\"ru\":\"Ирина Ковалёва\"}','2026-06-16 06:04:07'),
(53,'Главная · отзыв 2 · роль','home_r2_r','head of marketing','{\"en\":\"head of marketing\",\"ru\":\"руководитель маркетинга\"}','2026-06-16 06:04:07'),
(54,'Главная · отзыв 3 · цитата','home_r3_q','“Roles, action log, IP firewall — all out of the box. Security and audit were covered on day one.”','{\"en\":\"“Roles, action log, IP firewall — all out of the box. Security and audit were covered on day one.”\",\"ru\":\"«Роли, журнал действий, файрвол по IP — всё из коробки. Безопасность и аудит закрыли в первый день.»\"}','2026-06-16 06:04:07'),
(55,'Главная · отзыв 3 · имя','home_r3_n','Dmitry Vlasov','{\"en\":\"Dmitry Vlasov\",\"ru\":\"Дмитрий Власов\"}','2026-06-16 06:04:07'),
(56,'Главная · отзыв 3 · роль','home_r3_r','team lead','{\"en\":\"team lead\",\"ru\":\"тимлид\"}','2026-06-16 06:04:07'),
(57,'Главная · CTA · заголовок','home_cta_h2','Ready to build a site with AI?','{\"en\":\"Ready to build a site with AI?\",\"ru\":\"Готовы собрать сайт силами ИИ?\"}','2026-06-16 06:04:07'),
(58,'Главная · CTA · текст','home_cta_p','Describe the idea — X5-CMS assembles pages, templates and translations for you.','{\"en\":\"Describe the idea — X5-CMS assembles pages, templates and translations for you.\",\"ru\":\"Опишите идею — X5-CMS соберёт страницы, шаблоны и переводы за вас.\"}','2026-06-16 06:04:07'),
(59,'Главная · CTA · кнопка «Документация»','home_cta_docs','Documentation','{\"en\":\"Documentation\",\"ru\":\"Документация\"}','2026-06-16 06:04:07'),
(60,'Футер · подпись','footer_made','Built with Laravel + Filament','{\"en\":\"Built with Laravel + Filament\",\"ru\":\"Сделано на Laravel + Filament\"}','2026-06-16 06:06:12');

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

insert  into `cache`(`key`,`value`,`expiration`) values 
('laravel-cache-filament-edit-profile:1','i:1;',1781032398),
('laravel-cache-filament-edit-profile:1:timer','i:1781032398;',1781032398),
('laravel-cache-livewire-rate-limiter:d5277d1d845bd63f9a8287583b89a176f55c3103','i:1;',1781032398),
('laravel-cache-livewire-rate-limiter:d5277d1d845bd63f9a8287583b89a176f55c3103:timer','i:1781032398;',1781032398);

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `firewall_rules` */

DROP TABLE IF EXISTS `firewall_rules`;

CREATE TABLE `firewall_rules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `firewall_rules_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `firewall_rules` */

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint(5) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `languages` */

DROP TABLE IF EXISTS `languages`;

CREATE TABLE `languages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `languages_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `languages` */

insert  into `languages`(`id`,`code`,`name`,`is_default`,`is_active`,`sort_order`,`created_at`) values 
(1,'en','English',1,1,1,'2026-06-16 17:45:25'),
(2,'ru','Русский',0,1,2,'2026-06-16 17:45:25');

/*Table structure for table `messages_queue` */

DROP TABLE IF EXISTS `messages_queue`;

CREATE TABLE `messages_queue` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `channel` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_processed` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `messages_queue` */

insert  into `messages_queue`(`id`,`channel`,`body`,`is_processed`,`created_at`) values 
(1,'push','notify_service test',1,'2026-06-06 19:04:17'),
(2,'email','Hello World',1,'2026-06-06 19:08:26'),
(3,'email','Hello World',1,'2026-06-06 19:20:40'),
(4,'email','Hello World',0,'2026-06-06 19:54:48'),
(5,'telegram','Hello from Notify Service!',0,'2026-06-06 21:33:11'),
(6,'telegram','Hello from Notify Service!',0,'2026-06-06 21:36:04');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_06_06_165622_create_messages_queue_table',1),
(5,'2026_06_06_170749_add_is_processed_to_messages_queue_table',2),
(6,'2026_06_06_174334_create_docs_users_table',3),
(7,'2026_06_06_174740_add_auth_fields_to_docs_users_table',4),
(8,'2026_06_07_160953_add_locale_to_docs_users_table',5),
(9,'2026_06_07_200000_rebuild_users_table_from_docs_users',6),
(10,'2026_06_07_210000_add_last_login_at_to_users_table',7),
(11,'2026_06_07_220000_add_timezone_to_users_table',8),
(12,'2026_06_07_230000_create_roles_tables',9),
(13,'2026_06_07_240000_add_permissions_to_roles_table',10),
(14,'2026_06_07_250000_add_super_user_to_users_table',11),
(15,'2026_06_09_100000_create_firewall_rules_table',12),
(16,'2026_06_09_110000_create_action_logs_table',13),
(17,'2026_06_09_120000_add_name_to_users_table',14),
(18,'2026_06_09_130000_create_pages_table',15),
(19,'2026_06_09_140000_create_templates_and_link_pages',16),
(20,'2026_06_09_150000_add_system_flag_and_partials',17),
(21,'2026_06_09_160000_extract_head_partial',18),
(22,'2026_06_09_170000_dynamic_menu_template',19),
(23,'2026_06_09_180000_add_default_template',20),
(24,'2026_06_09_190000_add_seo_fields_to_pages',21),
(25,'2026_06_09_200000_head_template_seo_meta',22),
(26,'2026_06_09_210000_create_blocks_table',23),
(27,'2026_06_14_160000_create_translations_table',24),
(28,'2026_06_14_170000_create_settings_table',25),
(29,'2026_06_14_170100_add_settings_translations',25),
(30,'2026_06_14_170200_add_settings_custom_translations',26),
(31,'2026_06_14_180000_redesign_home_template',27),
(32,'2026_06_14_181000_home_template_ai_focus',28),
(33,'2026_06_14_182000_home_console_typewriter',29),
(34,'2026_06_14_183000_header_logo_and_reviews',30),
(35,'2026_06_14_184000_add_dashboard_translations',31),
(36,'2026_06_14_185000_add_i18n_to_pages',32),
(37,'2026_06_14_186000_i18n_templates',32),
(38,'2026_06_14_187000_add_i18n_to_blocks',33),
(39,'2026_06_15_100000_home_via_blocks',34),
(40,'2026_06_15_101000_footer_made_block',35),
(41,'2026_06_15_110000_create_languages_table',36),
(42,'2026_06_15_120000_languages_admin_strings',36),
(43,'2026_06_16_100000_breadcrumbs_partial',37),
(44,'2026_06_16_110000_poppins_font',38);

/*Table structure for table `pages` */

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `template_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `i18n` longtext COLLATE utf8mb4_unicode_ci,
  `meta_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_home` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_parent_id_index` (`parent_id`),
  KEY `pages_template_id_fk` (`template_id`),
  CONSTRAINT `pages_parent_id_fk` FOREIGN KEY (`parent_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pages_template_id_fk` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pages` */

insert  into `pages`(`id`,`parent_id`,`template_id`,`title`,`slug`,`content`,`i18n`,`meta_description`,`meta_keywords`,`meta_title`,`is_home`,`is_active`,`sort_order`,`created_at`) values 
(1,NULL,1,'Главная','home','<p>ewrqwerwerwer</p>','{\"en\":{\"title\":\"\\u0413\\u043b\\u0430\\u0432\\u043d\\u0430\\u044f\",\"content\":\"<p>ewrqwerwerwer<\\/p>\",\"meta_title\":\"Meta title\",\"meta_keywords\":\"Meta keywords\",\"meta_description\":\"Meta description\\n\"},\"ru\":{\"title\":\"\\u0413\\u043b\\u0430\\u0432\\u043d\\u0430\\u044f\",\"content\":\"<p>\\u044b\\u0432\\u0444\\u044b\\u0432<\\/p>\",\"meta_title\":\"Meta title\",\"meta_keywords\":\"Meta keywords\",\"meta_description\":\"Meta description\\n\"}}','Meta description\n','Meta keywords','Meta title',1,1,0,'2026-06-13 18:39:46'),
(13,NULL,6,'Test page','test','<p>test</p>','{\"en\":{\"title\":\"Test page\",\"content\":\"<h2>English version<\\/h2><p>This page is served at <b>\\/en\\/test<\\/b>. Switch language in the header to see the Russian version.<\\/p>\",\"meta_title\":\"Test page\",\"meta_description\":\"English demo page\",\"meta_keywords\":\"\"},\"ru\":{\"title\":\"\\u0422\\u0435\\u0441\\u0442\\u043e\\u0432\\u0430\\u044f \\u0441\\u0442\\u0440\\u0430\\u043d\\u0438\\u0446\\u0430\",\"content\":\"<h2>\\u0420\\u0443\\u0441\\u0441\\u043a\\u0430\\u044f \\u0432\\u0435\\u0440\\u0441\\u0438\\u044f<\\/h2><p>\\u042d\\u0442\\u0430 \\u0441\\u0442\\u0440\\u0430\\u043d\\u0438\\u0446\\u0430 \\u043e\\u0442\\u043a\\u0440\\u044b\\u0432\\u0430\\u0435\\u0442\\u0441\\u044f \\u043f\\u043e \\u0430\\u0434\\u0440\\u0435\\u0441\\u0443 <b>\\/ru\\/test<\\/b>. \\u041f\\u0435\\u0440\\u0435\\u043a\\u043b\\u044e\\u0447\\u0438\\u0442\\u0435 \\u044f\\u0437\\u044b\\u043a \\u0432 \\u0448\\u0430\\u043f\\u043a\\u0435, \\u0447\\u0442\\u043e\\u0431\\u044b \\u0443\\u0432\\u0438\\u0434\\u0435\\u0442\\u044c \\u0430\\u043d\\u0433\\u043b\\u0438\\u0439\\u0441\\u043a\\u0443\\u044e \\u0432\\u0435\\u0440\\u0441\\u0438\\u044e.<\\/p>\",\"meta_title\":\"\\u0422\\u0435\\u0441\\u0442\\u043e\\u0432\\u0430\\u044f \\u0441\\u0442\\u0440\\u0430\\u043d\\u0438\\u0446\\u0430\",\"meta_description\":\"\\u0414\\u0435\\u043c\\u043e-\\u0441\\u0442\\u0440\\u0430\\u043d\\u0438\\u0446\\u0430 \\u043d\\u0430 \\u0440\\u0443\\u0441\\u0441\\u043a\\u043e\\u043c\",\"meta_keywords\":\"\"}}',NULL,NULL,NULL,0,1,1,'2026-06-14 06:56:43'),
(15,13,6,'Test page (копия)','test-copy','<h2>English version</h2><p>This page is served at <strong>/en/test</strong>. Switch language in the header to see the Russian version.</p>','{\"en\":{\"title\":\"Test page (\\u043a\\u043e\\u043f\\u0438\\u044f)\",\"content\":\"<h2>English version<\\/h2><p>This page is served at <strong>\\/en\\/test<\\/strong>. Switch language in the header to see the Russian version.<\\/p>\",\"meta_title\":\"Test page\",\"meta_keywords\":null,\"meta_description\":\"English demo page\"},\"ru\":{\"title\":\"\\u0422\\u0435\\u0441\\u0442\\u043e\\u0432\\u0430\\u044f \\u0441\\u0442\\u0440\\u0430\\u043d\\u0438\\u0446\\u0430 (\\u043a\\u043e\\u043f\\u0438\\u044f)\",\"content\":\"<h2>\\u0420\\u0443\\u0441\\u0441\\u043a\\u0430\\u044f \\u0432\\u0435\\u0440\\u0441\\u0438\\u044f<\\/h2><p>\\u042d\\u0442\\u0430 \\u0441\\u0442\\u0440\\u0430\\u043d\\u0438\\u0446\\u0430 \\u043e\\u0442\\u043a\\u0440\\u044b\\u0432\\u0430\\u0435\\u0442\\u0441\\u044f \\u043f\\u043e \\u0430\\u0434\\u0440\\u0435\\u0441\\u0443 <strong>\\/ru\\/test<\\/strong>. \\u041f\\u0435\\u0440\\u0435\\u043a\\u043b\\u044e\\u0447\\u0438\\u0442\\u0435 \\u044f\\u0437\\u044b\\u043a \\u0432 \\u0448\\u0430\\u043f\\u043a\\u0435, \\u0447\\u0442\\u043e\\u0431\\u044b \\u0443\\u0432\\u0438\\u0434\\u0435\\u0442\\u044c \\u0430\\u043d\\u0433\\u043b\\u0438\\u0439\\u0441\\u043a\\u0443\\u044e \\u0432\\u0435\\u0440\\u0441\\u0438\\u044e.<\\/p>\",\"meta_title\":\"\\u0422\\u0435\\u0441\\u0442\\u043e\\u0432\\u0430\\u044f \\u0441\\u0442\\u0440\\u0430\\u043d\\u0438\\u0446\\u0430\",\"meta_keywords\":null,\"meta_description\":\"\\u0414\\u0435\\u043c\\u043e-\\u0441\\u0442\\u0440\\u0430\\u043d\\u0438\\u0446\\u0430 \\u043d\\u0430 \\u0440\\u0443\\u0441\\u0441\\u043a\\u043e\\u043c\"}}','English demo page',NULL,'Test page',0,1,1,'2026-06-16 17:52:16'),
(16,15,6,'Test page (копия) (копия)','test-copy-copy','<h2>English version</h2><p>This page is served at <strong>/en/test</strong>. Switch language in the header to see the Russian version.</p>','{\"en\":{\"title\":\"Test page (\\u043a\\u043e\\u043f\\u0438\\u044f) (\\u043a\\u043e\\u043f\\u0438\\u044f)\",\"content\":\"<h2>English version<\\/h2><p>This page is served at <strong>\\/en\\/test<\\/strong>. Switch language in the header to see the Russian version.<\\/p>\",\"meta_title\":\"Test page\",\"meta_keywords\":null,\"meta_description\":\"English demo page\"},\"ru\":{\"title\":\"\\u0422\\u0435\\u0441\\u0442\\u043e\\u0432\\u0430\\u044f \\u0441\\u0442\\u0440\\u0430\\u043d\\u0438\\u0446\\u0430 (\\u043a\\u043e\\u043f\\u0438\\u044f) (\\u043a\\u043e\\u043f\\u0438\\u044f)\",\"content\":\"<h2>\\u0420\\u0443\\u0441\\u0441\\u043a\\u0430\\u044f \\u0432\\u0435\\u0440\\u0441\\u0438\\u044f<\\/h2><p>\\u042d\\u0442\\u0430 \\u0441\\u0442\\u0440\\u0430\\u043d\\u0438\\u0446\\u0430 \\u043e\\u0442\\u043a\\u0440\\u044b\\u0432\\u0430\\u0435\\u0442\\u0441\\u044f \\u043f\\u043e \\u0430\\u0434\\u0440\\u0435\\u0441\\u0443 <strong>\\/ru\\/test<\\/strong>. \\u041f\\u0435\\u0440\\u0435\\u043a\\u043b\\u044e\\u0447\\u0438\\u0442\\u0435 \\u044f\\u0437\\u044b\\u043a \\u0432 \\u0448\\u0430\\u043f\\u043a\\u0435, \\u0447\\u0442\\u043e\\u0431\\u044b \\u0443\\u0432\\u0438\\u0434\\u0435\\u0442\\u044c \\u0430\\u043d\\u0433\\u043b\\u0438\\u0439\\u0441\\u043a\\u0443\\u044e \\u0432\\u0435\\u0440\\u0441\\u0438\\u044e.<\\/p>\",\"meta_title\":\"\\u0422\\u0435\\u0441\\u0442\\u043e\\u0432\\u0430\\u044f \\u0441\\u0442\\u0440\\u0430\\u043d\\u0438\\u0446\\u0430\",\"meta_keywords\":null,\"meta_description\":\"\\u0414\\u0435\\u043c\\u043e-\\u0441\\u0442\\u0440\\u0430\\u043d\\u0438\\u0446\\u0430 \\u043d\\u0430 \\u0440\\u0443\\u0441\\u0441\\u043a\\u043e\\u043c\"}}','English demo page',NULL,'Test page',0,1,1,'2026-06-16 19:16:22');

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `role_user` */

DROP TABLE IF EXISTS `role_user`;

CREATE TABLE `role_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_user_unique` (`role_id`,`user_id`),
  KEY `role_user_user_id_index` (`user_id`),
  CONSTRAINT `role_user_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_user` */

insert  into `role_user`(`id`,`role_id`,`user_id`) values 
(5,1,1),
(6,5,2);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`description`,`permissions`,`created_at`) values 
(1,'админы','админы','[\"api.messages.view\",\"api.messages.create\",\"api.messages.update\",\"api.messages.delete\",\"system.users.view\",\"system.users.create\",\"system.users.update\",\"system.users.delete\",\"system.roles.view\",\"system.roles.create\",\"system.roles.update\",\"system.roles.delete\"]','2026-06-08 22:43:20'),
(5,'оператор','оператор','[\"api.messages.view\",\"api.messages.create\",\"api.messages.update\",\"api.messages.delete\"]','2026-06-08 23:02:13');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('P5ltN0ew34vTjoCIWJ2fNmGix1IZ94MLprNFCNOl',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiJhWkRYVXVJT01qRVdVUkQ3V3ZxUmxKZUowYzFyYzdhbGRYUWFqR2FoIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvcnVcL3Rlc3RcL3Rlc3QtY29weSIsInJvdXRlIjoiY21zLnBhZ2UifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1781625292),
('RiyBwSOgUDgN5oPmwlyrjP6Mru9ce3wooX4dk6CA',NULL,'127.0.0.1','Symfony','eyJfdG9rZW4iOiI5WkhlTm84WU9TUExDZUJiUkQ3bExMSGl3dllPcGUyMGFSenoyQmZCIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvZW4iLCJyb3V0ZSI6ImNtcy5ob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1781624745),
('vD9iF8GvUwKXLYcPl231Uq6YsIzdpM3lJ3aU1g1L',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJjOHhtRmJreWpnZ3lTQ1IzV0pwbVluc3NqQUpOWVhSSnRFTkIyR055IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wveDUubG9jYWxcL2VuIiwicm91dGUiOiJjbXMuaG9tZSJ9LCJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJwYXNzd29yZF9oYXNoX2FkbWluIjoiYjE5MDViZDM3YzMwNjJjMzQxNDgyMmRmMThmYmM4MDkyMjYxYTI5NTlhNTJjNzBiZDJjMGZmZjI2NGJhMTU3MyIsInRhYmxlcyI6eyI3ZmU3Y2QyMWNlNGI2Mzc0NGVhMTg3MDhhNjRjNmJkNF9jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImlkIiwibGFiZWwiOiJJRCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJuYW1lIiwibGFiZWwiOiJcdTA0MWRcdTA0MzBcdTA0MzdcdTA0MzJcdTA0MzBcdTA0M2RcdTA0MzhcdTA0MzUiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoic2x1ZyIsImxhYmVsIjoiXHUwNDEyXHUwNDQxXHUwNDQyXHUwNDMwXHUwNDMyXHUwNDNhXHUwNDMwIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InZhbHVlIiwibGFiZWwiOiJcdTA0MTdcdTA0M2RcdTA0MzBcdTA0NDdcdTA0MzVcdTA0M2RcdTA0MzhcdTA0MzUiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiY3JlYXRlZF9hdCIsImxhYmVsIjoiXHUwNDIxXHUwNDNlXHUwNDM3XHUwNDM0XHUwNDMwXHUwNDNkIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpmYWxzZX1dLCJiZDI2MGJiZjdiOWRkZmVmYTUxMGNlMjc4YzliMGM0OF9jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InRpdGxlIiwibGFiZWwiOiJcdTA0MTdcdTA0MzBcdTA0MzNcdTA0M2VcdTA0M2JcdTA0M2VcdTA0MzJcdTA0M2VcdTA0M2EiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoic2x1ZyIsImxhYmVsIjoiVVJMIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InRlbXBsYXRlLm5hbWUiLCJsYWJlbCI6Ilx1MDQyOFx1MDQzMFx1MDQzMVx1MDQzYlx1MDQzZVx1MDQzZCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpc19ob21lIiwibGFiZWwiOiJcdTA0MTNcdTA0M2JcdTA0MzBcdTA0MzJcdTA0M2RcdTA0MzBcdTA0NGYiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXNfYWN0aXZlIiwibGFiZWwiOiJcdTA0MTBcdTA0M2FcdTA0NDJcdTA0MzhcdTA0MzJcdTA0M2RcdTA0MzAiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoic29ydF9vcmRlciIsImxhYmVsIjoiXHUwNDFmXHUwNDNlXHUwNDQwXHUwNDRmXHUwNDM0XHUwNDNlXHUwNDNhIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNyZWF0ZWRfYXQiLCJsYWJlbCI6Ilx1MDQyMVx1MDQzZVx1MDQzN1x1MDQzNFx1MDQzMFx1MDQzZFx1MDQzMCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOnRydWUsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6ZmFsc2V9XSwiYTg0OTE4N2EwZGZkZGU3YTg2MWZhNjcxN2MzMjljNjFfY29sdW1ucyI6W3sidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpZCIsImxhYmVsIjoiSUQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoibmFtZSIsImxhYmVsIjoiXHUwNDFkXHUwNDMwXHUwNDM3XHUwNDMyXHUwNDMwXHUwNDNkXHUwNDM4XHUwNDM1IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InNsdWciLCJsYWJlbCI6IlNsdWciLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXNfc3lzdGVtIiwibGFiZWwiOiJcdTA0MjFcdTA0MzhcdTA0NDFcdTA0NDJcdTA0MzVcdTA0M2NcdTA0M2RcdTA0NGJcdTA0MzkiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXNfZGVmYXVsdCIsImxhYmVsIjoiXHUwNDFmXHUwNDNlIFx1MDQ0M1x1MDQzY1x1MDQzZVx1MDQzYlx1MDQ0N1x1MDQzMFx1MDQzZFx1MDQzOFx1MDQ0ZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJwYWdlc19jb3VudCIsImxhYmVsIjoiXHUwNDIxXHUwNDQyXHUwNDQwXHUwNDMwXHUwNDNkXHUwNDM4XHUwNDQ2IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNyZWF0ZWRfYXQiLCJsYWJlbCI6Ilx1MDQyMVx1MDQzZVx1MDQzN1x1MDQzNFx1MDQzMFx1MDQzZCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOnRydWUsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6ZmFsc2V9XSwiNmRkM2M1ZTFmNjU4MDAzZGM0NWUzMTAwZGMzN2UyNmRfY29sdW1ucyI6W3sidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpZCIsImxhYmVsIjoiSUQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXBfYWRkcmVzcyIsImxhYmVsIjoiSVAtXHUwNDMwXHUwNDM0XHUwNDQwXHUwNDM1XHUwNDQxIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImRlc2NyaXB0aW9uIiwibGFiZWwiOiJcdTA0MWVcdTA0M2ZcdTA0MzhcdTA0NDFcdTA0MzBcdTA0M2RcdTA0MzhcdTA0MzUiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXNfYWN0aXZlIiwibGFiZWwiOiJcdTA0MTBcdTA0M2FcdTA0NDJcdTA0MzhcdTA0MzJcdTA0M2RcdTA0M2UiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiY3JlYXRlZF9hdCIsImxhYmVsIjoiXHUwNDIxXHUwNDNlXHUwNDM3XHUwNDM0XHUwNDMwXHUwNDNkXHUwNDNlIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH1dLCI0ODJlZTcxMGEzN2ZhOTg2MDZlNjNkNmZiNjlkZjAxMF9jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNyZWF0ZWRfYXQiLCJsYWJlbCI6Ilx1MDQxMlx1MDQ0MFx1MDQzNVx1MDQzY1x1MDQ0ZiIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJ1c2VyX2xvZ2luIiwibGFiZWwiOiJcdTA0MWZcdTA0M2VcdTA0M2JcdTA0NGNcdTA0MzdcdTA0M2VcdTA0MzJcdTA0MzBcdTA0NDJcdTA0MzVcdTA0M2JcdTA0NGMiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiZXZlbnQiLCJsYWJlbCI6Ilx1MDQxNFx1MDQzNVx1MDQzOVx1MDQ0MVx1MDQ0Mlx1MDQzMlx1MDQzOFx1MDQzNSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzdWJqZWN0X2xhYmVsIiwibGFiZWwiOiJcdTA0MjBcdTA0MzBcdTA0MzdcdTA0MzRcdTA0MzVcdTA0M2IiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoic3ViamVjdF9pZCIsImxhYmVsIjoiSUQgXHUwNDM3XHUwNDMwXHUwNDNmXHUwNDM4XHUwNDQxXHUwNDM4IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InByb3BlcnRpZXMiLCJsYWJlbCI6Ilx1MDQxNFx1MDQzNVx1MDQ0Mlx1MDQzMFx1MDQzYlx1MDQzOCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpcF9hZGRyZXNzIiwibGFiZWwiOiJJUC1cdTA0MzBcdTA0MzRcdTA0NDBcdTA0MzVcdTA0NDEiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjp0cnVlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOmZhbHNlfV0sIjQ1ZWNmNDNhYWRkNmFmYjc2YmUyZTFkMmJiNjVhYjdiX2NvbHVtbnMiOlt7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaWQiLCJsYWJlbCI6IklEIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Im5hbWUiLCJsYWJlbCI6Ilx1MDQxZFx1MDQzMFx1MDQzN1x1MDQzMlx1MDQzMFx1MDQzZFx1MDQzOFx1MDQzNSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJkZXNjcmlwdGlvbiIsImxhYmVsIjoiXHUwNDFlXHUwNDNmXHUwNDM4XHUwNDQxXHUwNDMwXHUwNDNkXHUwNDM4XHUwNDM1IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InVzZXJzX2NvdW50IiwibGFiZWwiOiJcdTA0MWZcdTA0M2VcdTA0M2JcdTA0NGNcdTA0MzdcdTA0M2VcdTA0MzJcdTA0MzBcdTA0NDJcdTA0MzVcdTA0M2JcdTA0MzVcdTA0MzkiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicGVybWlzc2lvbnNfY291bnQiLCJsYWJlbCI6Ilx1MDQxZlx1MDQ0MFx1MDQzMFx1MDQzMiIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJjcmVhdGVkX2F0IiwibGFiZWwiOiJcdTA0MjFcdTA0M2VcdTA0MzdcdTA0MzRcdTA0MzBcdTA0M2RcdTA0MzAiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfV0sImU2ZTAyNWM5N2I0ZmE0ZmI2NzRiMTg4ZWEzODNhNzdlX2NvbHVtbnMiOlt7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaWQiLCJsYWJlbCI6IklEIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImxvZ2luIiwibGFiZWwiOiJcdTA0MWJcdTA0M2VcdTA0MzNcdTA0MzhcdTA0M2QiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoibmFtZSIsImxhYmVsIjoiXHUwNDE4XHUwNDNjXHUwNDRmIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImlzX2FjdGl2ZSIsImxhYmVsIjoiXHUwNDEwXHUwNDNhXHUwNDQyXHUwNDM4XHUwNDMyXHUwNDM1XHUwNDNkIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InJvbGVzLm5hbWUiLCJsYWJlbCI6Ilx1MDQyMFx1MDQzZVx1MDQzYlx1MDQzOCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzdXBlcl91c2VyIiwibGFiZWwiOiJcdTA0MjFcdTA0NDNcdTA0M2ZcdTA0MzVcdTA0NDAiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiZmFpbGVkX2F0dGVtcHRzIiwibGFiZWwiOiJcdTA0MWZcdTA0M2VcdTA0M2ZcdTA0NGJcdTA0NDJcdTA0M2VcdTA0M2EiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiY3JlYXRlZF9hdCIsImxhYmVsIjoiXHUwNDIxXHUwNDNlXHUwNDM3XHUwNDM0XHUwNDMwXHUwNDNkIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Imxhc3RfbG9naW5fYXQiLCJsYWJlbCI6Ilx1MDQxZlx1MDQzZVx1MDQ0MVx1MDQzYlx1MDQzNVx1MDQzNFx1MDQzZFx1MDQzOFx1MDQzOSBcdTA0MzJcdTA0NDVcdTA0M2VcdTA0MzQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfV0sImVkMzNiMWI1NDIzNjY4YTUzNGUxZDE2ZWY5OWFlYmNkX2NvbHVtbnMiOlt7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaWQiLCJsYWJlbCI6IklEIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImxvY2FsZSIsImxhYmVsIjoiXHUwNDFiXHUwNDNlXHUwNDNhXHUwNDMwXHUwNDNiXHUwNDRjIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Imdyb3VwIiwibGFiZWwiOiJcdTA0MTNcdTA0NDBcdTA0NDNcdTA0M2ZcdTA0M2ZcdTA0MzAiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoia2V5IiwibGFiZWwiOiJcdTA0MWFcdTA0M2JcdTA0NGVcdTA0NDciLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoidmFsdWUiLCJsYWJlbCI6Ilx1MDQxN1x1MDQzZFx1MDQzMFx1MDQ0N1x1MDQzNVx1MDQzZFx1MDQzOFx1MDQzNSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9XSwiZTBhYTNmNmNhYTcyMDRlMjdhOTcyZDE1ZGYwNDczNWFfY29sdW1ucyI6W3sidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpZCIsImxhYmVsIjoiSUQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiY29kZSIsImxhYmVsIjoiXHUwNDFhXHUwNDNlXHUwNDM0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6Im5hbWUiLCJsYWJlbCI6Ilx1MDQxZFx1MDQzMFx1MDQzN1x1MDQzMlx1MDQzMFx1MDQzZFx1MDQzOFx1MDQzNSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpc19kZWZhdWx0IiwibGFiZWwiOiJcdTA0MWZcdTA0M2UgXHUwNDQzXHUwNDNjXHUwNDNlXHUwNDNiXHUwNDQ3XHUwNDMwXHUwNDNkXHUwNDM4XHUwNDRlIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImlzX2FjdGl2ZSIsImxhYmVsIjoiXHUwNDEwXHUwNDNhXHUwNDQyXHUwNDM4XHUwNDMyXHUwNDM1XHUwNDNkIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6InNvcnRfb3JkZXIiLCJsYWJlbCI6Ilx1MDQxZlx1MDQzZVx1MDQ0MFx1MDQ0Zlx1MDQzNFx1MDQzZVx1MDQzYSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJjcmVhdGVkX2F0IiwibGFiZWwiOiJcdTA0MjFcdTA0M2VcdTA0MzdcdTA0MzRcdTA0MzBcdTA0M2QiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjp0cnVlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOmZhbHNlfV0sIjk4YjRjMTc5NmJlNjBkMmUzZDZlZmE2MDAzYjQ2NWQyX2NvbHVtbnMiOlt7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaWQiLCJsYWJlbCI6IklEIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImNoYW5uZWwiLCJsYWJlbCI6Ilx1MDQxYVx1MDQzMFx1MDQzZFx1MDQzMFx1MDQzYiIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJib2R5IiwibGFiZWwiOiJcdTA0MjFcdTA0M2VcdTA0M2VcdTA0MzFcdTA0NDlcdTA0MzVcdTA0M2RcdTA0MzhcdTA0MzUiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiaXNfcHJvY2Vzc2VkIiwibGFiZWwiOiJcdTA0MjFcdTA0NDJcdTA0MzBcdTA0NDJcdTA0NDNcdTA0NDEiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiY3JlYXRlZF9hdCIsImxhYmVsIjoiXHUwNDIxXHUwNDNlXHUwNDM3XHUwNDM0XHUwNDMwXHUwNDNkXHUwNDNlIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH1dfSwiZmlsYW1lbnQiOltdfQ==',1781630356),
('ZIgdmtBYaLciInpPFXmSQioaHIDlAEriUE9A5sxH',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJSNzl6VFdxdk40WXFyNUV0WW1ySkJuenE0Mnc1V3FoNmlaT3BxTmc1IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3g1LmxvY2FsXC9hZG1pbiIsInJvdXRlIjoiZmlsYW1lbnQuYWRtaW4ucGFnZXMuZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXJsIjpbXSwibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF9hZG1pbiI6ImIxOTA1YmQzN2MzMDYyYzM0MTQ4MjJkZjE4ZmJjODA5MjI2MWEyOTU5YTUyYzcwYmQyYzBmZmYyNjRiYTE1NzMifQ==',1781630130);

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `settings` */

insert  into `settings`(`id`,`key`,`value`,`created_at`) values 
(1,'site_name','X5-CMS','2026-06-14 20:26:50'),
(2,'site_tagline','123123','2026-06-14 20:26:50'),
(3,'seo_title_suffix','','2026-06-14 20:26:50'),
(4,'seo_default_description','','2026-06-14 20:26:50'),
(5,'seo_default_keywords','','2026-06-14 20:26:50'),
(6,'seo_index','1','2026-06-14 20:26:50'),
(7,'maintenance_mode','0','2026-06-14 20:26:50'),
(8,'maintenance_message','Сайт временно на обслуживании. Скоро вернёмся.','2026-06-14 20:26:50'),
(9,'contact_email','','2026-06-14 20:26:50'),
(10,'site_logo','','2026-06-14 20:28:46'),
(11,'site_favicon','','2026-06-14 20:28:46'),
(12,'analytics_head','','2026-06-14 20:28:46'),
(13,'analytics_body','','2026-06-14 20:28:46'),
(15,'qweqweqwe','qweqwe','2026-06-14 20:35:07');

/*Table structure for table `templates` */

DROP TABLE IF EXISTS `templates`;

CREATE TABLE `templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `body` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `templates_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `templates` */

insert  into `templates`(`id`,`name`,`slug`,`is_system`,`is_default`,`body`,`created_at`) values 
(1,'Главная (лендинг)','home',0,0,'<!DOCTYPE html>\n<html lang=\"{{ $locale ?? \'en\' }}\">\n@partial(\'head\')\n<body>\n    @partial(\'header\')\n\n    <section class=\"hero\">\n        <div class=\"wrap\">\n            <div class=\"hero-copy\">\n                <span class=\"badge anim d1\"><span class=\"dot\"></span> {{ $appName }} — @block(\'home_hero_badge\')</span>\n                <h1 class=\"anim d2\">@block(\'home_hero_title1\')<br><span class=\"grad\">@block(\'home_hero_title2\')</span></h1>\n                <p class=\"lead anim d3\">@block(\'home_hero_lead\')</p>\n                <div class=\"actions anim d4\">\n                    <a href=\"/admin\" class=\"btn btn-primary\">@block(\'home_btn_start\')</a>\n                    <a href=\"#features\" class=\"btn btn-ghost\">@block(\'home_btn_features\')</a>\n                </div>\n                <div class=\"trust anim d5\">\n                    <span>@block(\'home_trust1\')</span><span>@block(\'home_trust2\')</span><span>Filament v4</span><span>i18n</span><span>Laravel 13</span>\n                </div>\n            </div>\n            <div class=\"hero-visual anim d3\">\n                <div class=\"code-card\">\n                    <div class=\"bar\"><i></i><i></i><i></i><small>X5 AI · console</small></div>\n<pre><span class=\"ai\">✦ X5 AI</span>\n\n<span class=\"u\">› <span class=\"tw\">@block(\'home_console_prompt\')</span><span class=\"cur\">▍</span></span>\n\n<span class=\"line\"><span class=\"ok\">✓</span> @block(\'home_console1\')</span>\n<span class=\"line\"><span class=\"ok\">✓</span> @block(\'home_console2\')</span>\n<span class=\"line\"><span class=\"ok\">✓</span> @block(\'home_console3\')</span>\n<span class=\"line\"><span class=\"ok\">✓</span> @block(\'home_console4\') <span class=\"m\">→ /tariffs</span></span></pre>\n                </div>\n            </div>\n        </div>\n    </section>\n\n    <section class=\"stats\">\n        <div class=\"wrap\">\n            <div class=\"stat\"><b>AI</b><span>@block(\'home_stat1\')</span></div>\n            <div class=\"stat\"><b>0</b><span>@block(\'home_stat2\')</span></div>\n            <div class=\"stat\"><b>i18n</b><span>@block(\'home_stat3\')</span></div>\n            <div class=\"stat\"><b>100%</b><span>@block(\'home_stat4\')</span></div>\n        </div>\n    </section>\n\n    <section class=\"features\" id=\"features\">\n        <div class=\"wrap\">\n            <div class=\"sec-head\">\n                <div class=\"eyebrow\">@block(\'home_feat_eyebrow\')</div>\n                <h2>@block(\'home_feat_h2\')</h2>\n                <p>@block(\'home_feat_sub\')</p>\n            </div>\n            <div class=\"grid\">\n                <div class=\"feature\">\n                    <div class=\"ic\"><svg viewBox=\"0 0 24 24\"><path d=\"M12 3l1.7 4.3L18 9l-4.3 1.7L12 15l-1.7-4.3L6 9l4.3-1.7L12 3z\"/><path d=\"M19 14l.8 2 2 .8-2 .8-.8 2-.8-2-2-.8 2-.8 .8-2z\"/></svg></div>\n                    <h3>@block(\'home_f1_t\')</h3>\n                    <p>@block(\'home_f1_d\')</p>\n                </div>\n                <div class=\"feature\">\n                    <div class=\"ic\"><svg viewBox=\"0 0 24 24\"><path d=\"M5 19L15 9\"/><path d=\"M17 3l.9 2.1L20 6l-2.1.9L17 9l-.9-2.1L14 6l2.1-.9L17 3z\"/></svg></div>\n                    <h3>@block(\'home_f2_t\')</h3>\n                    <p>@block(\'home_f2_d\')</p>\n                </div>\n                <div class=\"feature\">\n                    <div class=\"ic\"><svg viewBox=\"0 0 24 24\"><rect x=\"3\" y=\"3\" width=\"18\" height=\"18\" rx=\"2\"/><path d=\"M3 9h18\"/><path d=\"M9 9v12\"/></svg></div>\n                    <h3>@block(\'home_f3_t\')</h3>\n                    <p>@block(\'home_f3_d\')</p>\n                </div>\n                <div class=\"feature\">\n                    <div class=\"ic\"><svg viewBox=\"0 0 24 24\"><circle cx=\"12\" cy=\"12\" r=\"9\"/><path d=\"M3 12h18\"/><path d=\"M12 3c2.6 2.7 2.6 15.3 0 18\"/><path d=\"M12 3c-2.6 2.7-2.6 15.3 0 18\"/></svg></div>\n                    <h3>@block(\'home_f4_t\')</h3>\n                    <p>@block(\'home_f4_d\')</p>\n                </div>\n                <div class=\"feature\">\n                    <div class=\"ic\"><svg viewBox=\"0 0 24 24\"><path d=\"M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z\"/><path d=\"M9 12l2 2 4-4\"/></svg></div>\n                    <h3>@block(\'home_f5_t\')</h3>\n                    <p>@block(\'home_f5_d\')</p>\n                </div>\n                <div class=\"feature\">\n                    <div class=\"ic\"><svg viewBox=\"0 0 24 24\"><path d=\"M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z\"/><path d=\"M14 2v6h6\"/><path d=\"M8 13h8\"/><path d=\"M8 17h8\"/></svg></div>\n                    <h3>@block(\'home_f6_t\')</h3>\n                    <p>@block(\'home_f6_d\')</p>\n                </div>\n            </div>\n        </div>\n    </section>\n\n    <section class=\"steps\">\n        <div class=\"wrap\">\n            <div class=\"sec-head\">\n                <div class=\"eyebrow\">@block(\'home_steps_eyebrow\')</div>\n                <h2>@block(\'home_steps_h2\')</h2>\n                <p>@block(\'home_steps_sub\')</p>\n            </div>\n            <div class=\"steps-grid\">\n                <div class=\"step\"><div class=\"n\">1</div><h3>@block(\'home_s1_t\')</h3><p>@block(\'home_s1_d\')</p></div>\n                <div class=\"step\"><div class=\"n\">2</div><h3>@block(\'home_s2_t\')</h3><p>@block(\'home_s2_d\')</p></div>\n                <div class=\"step\"><div class=\"n\">3</div><h3>@block(\'home_s3_t\')</h3><p>@block(\'home_s3_d\')</p></div>\n            </div>\n        </div>\n    </section>\n\n    @if (filled($content))\n        <section class=\"content\" id=\"content\">\n            <div class=\"wrap\"><div class=\"card\">{!! $content !!}</div></div>\n        </section>\n    @endif\n\n    @if ($children->isNotEmpty())\n        <section class=\"subpages\">\n            <div class=\"wrap\">\n                <h2>@block(\'home_steps_eyebrow\')</h2>\n                <div class=\"grid\">\n                    @foreach ($children as $child)\n                        <a href=\"{{ $child->url }}\">{{ $child->tr(\'title\') }} →</a>\n                    @endforeach\n                </div>\n            </div>\n        </section>\n    @endif\n\n    <section class=\"reviews\">\n        <div class=\"wrap\">\n            <div class=\"sec-head\">\n                <div class=\"eyebrow\">@block(\'home_rev_eyebrow\')</div>\n                <h2>@block(\'home_rev_h2\')</h2>\n                <p>@block(\'home_rev_sub\')</p>\n            </div>\n            <div class=\"grid\">\n                <div class=\"review\">\n                    <div class=\"stars\">★★★★★</div>\n                    <p>@block(\'home_r1_q\')</p>\n                    <div class=\"who\"><div class=\"av\">АМ</div><div><b>@block(\'home_r1_n\')</b><span>@block(\'home_r1_r\')</span></div></div>\n                </div>\n                <div class=\"review\">\n                    <div class=\"stars\">★★★★★</div>\n                    <p>@block(\'home_r2_q\')</p>\n                    <div class=\"who\"><div class=\"av\">ИК</div><div><b>@block(\'home_r2_n\')</b><span>@block(\'home_r2_r\')</span></div></div>\n                </div>\n                <div class=\"review\">\n                    <div class=\"stars\">★★★★★</div>\n                    <p>@block(\'home_r3_q\')</p>\n                    <div class=\"who\"><div class=\"av\">ДВ</div><div><b>@block(\'home_r3_n\')</b><span>@block(\'home_r3_r\')</span></div></div>\n                </div>\n            </div>\n        </div>\n    </section>\n\n    <section class=\"cta\">\n        <div class=\"wrap\">\n            <div class=\"band\">\n                <h2>@block(\'home_cta_h2\')</h2>\n                <p>@block(\'home_cta_p\')</p>\n                <div class=\"actions\">\n                    <a href=\"/admin\" class=\"btn btn-primary\">@block(\'home_btn_start\')</a>\n                    <a href=\"/docs\" class=\"btn btn-ghost\">@block(\'home_cta_docs\')</a>\n                </div>\n            </div>\n        </div>\n    </section>\n\n    @partial(\'footer\')\n\n    <script>\n    (function () {\n        var card = document.querySelector(\'.code-card\');\n        if (!card) return;\n        var tw = card.querySelector(\'.tw\');\n        var cur = card.querySelector(\'.cur\');\n        var lines = card.querySelectorAll(\'.line\');\n        if (!tw) return;\n        var text = tw.textContent;\n        if (window.matchMedia && window.matchMedia(\'(prefers-reduced-motion: reduce)\').matches) return;\n        function run() {\n            card.classList.add(\'tw-on\');\n            tw.textContent = \'\';\n            for (var j = 0; j < lines.length; j++) lines[j].classList.remove(\'show\');\n            var i = 0;\n            (function type() {\n                tw.textContent = text.slice(0, i);\n                if (i < text.length) { i++; setTimeout(type, 42 + Math.random() * 45); return; }\n                for (var k = 0; k < lines.length; k++) {\n                    (function (el, idx) { setTimeout(function () { el.classList.add(\'show\'); }, 420 + idx * 430); })(lines[k], k);\n                }\n                setTimeout(run, 420 + lines.length * 430 + 4200);\n            })();\n        }\n        if (\'IntersectionObserver\' in window) {\n            var io = new IntersectionObserver(function (e) { if (e[0].isIntersecting) { io.disconnect(); run(); } });\n            io.observe(card);\n        } else { run(); }\n    })();\n    </script>\n</body>\n</html>','2026-06-13 18:53:37'),
(2,'Шапка сайта','header',1,0,'<header class=\"nav\">\n    <div class=\"wrap\">\n        <a href=\"{{ url($locale ?? \'en\') }}\" class=\"brand\"><img class=\"logo\" src=\"{{ asset(\'images/logo-mark.svg\') }}\" alt=\"{{ $appName }}\"> {{ $appName }}</a>\n        @partial(\'menu\')\n        <div class=\"lang\">\n            @foreach (\\App\\Modules\\System\\Models\\Language::codes() as $l)\n                <a href=\"{{ $page->urlFor($l) }}\" class=\"{{ ($locale ?? \'\') === $l ? \'on\' : \'\' }}\">{{ strtoupper($l) }}</a>\n            @endforeach\n        </div>\n        <a href=\"/admin\" class=\"btn btn-primary\">{{ ($locale ?? \'\') === \'en\' ? \'Sign in\' : \'Войти\' }}</a>\n    </div>\n</header>','2026-06-13 19:10:02'),
(3,'Меню','menu',1,0,'<nav class=\"nav-links\">\n    <a href=\"{{ url($locale ?? \'en\') }}\">{{ ($locale ?? \'\') === \'en\' ? \'Home\' : \'Главная\' }}</a>\n    @foreach (\\App\\Modules\\Cms\\Models\\Page::navItems() as $item)\n        <a href=\"{{ $item->url }}\">{{ $item->tr(\'title\') }}</a>\n    @endforeach\n    <a href=\"/docs\">API</a>\n</nav>','2026-06-13 19:10:02'),
(4,'Подвал сайта','footer',1,0,'<footer class=\"site\">\n    <div class=\"wrap\">\n        <span>© {{ date(\'Y\') }} {{ $appName }}</span>\n        <span>@block(\'footer_made\')</span>\n    </div>\n</footer>','2026-06-13 19:10:02'),
(5,'Head (мета и стили)','head',1,0,'<head>\n    <meta charset=\"utf-8\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n    <title>{{ ($metaTitle ?? null) ?: $title }} — {{ $appName }}</title>\n    @if (!empty($metaDescription))<meta name=\"description\" content=\"{{ $metaDescription }}\">@endif\n    @if (!empty($metaKeywords))<meta name=\"keywords\" content=\"{{ $metaKeywords }}\">@endif\n    @foreach (\\App\\Modules\\System\\Models\\Language::codes() as $l)\n        <link rel=\"alternate\" hreflang=\"{{ $l }}\" href=\"{{ $page->urlFor($l) }}\">\n    @endforeach\n    <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">\n <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>\n <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap\">\n <link rel=\"stylesheet\" href=\"{{ asset(\'css/landing.css\') }}?v={{ filemtime(public_path(\'css/landing.css\')) }}\">\n</head>','2026-06-14 06:51:27'),
(6,'Стандартная страница','default',0,1,'<!DOCTYPE html>\n<html lang=\"ru\">\n@partial(\'head\')\n<body>\n    @partial(\'header\')\n @partial(\'breadcrumbs\')\n\n    <section class=\"content\" id=\"content\">\n        <div class=\"wrap\">\n            <div class=\"card\">\n                <h1 style=\"margin-top:0;\">{{ $title }}</h1>\n                @if (filled($content))\n                    {!! $content !!}\n                @else\n                    <p class=\"placeholder\">Содержимое страницы пока не заполнено.</p>\n                @endif\n            </div>\n        </div>\n    </section>\n\n    @if ($children->isNotEmpty())\n        <section class=\"subpages\">\n            <div class=\"wrap\">\n                <h2>Разделы</h2>\n                <div class=\"grid\">\n                    @foreach ($children as $child)\n                        <a href=\"{{ $child->url }}\">{{ $child->title }} →</a>\n                    @endforeach\n                </div>\n            </div>\n        </section>\n    @endif\n\n    @partial(\'footer\')\n</body>\n</html>','2026-06-14 06:58:33'),
(7,'Хлебные крошки','breadcrumbs',1,0,'@if (! $page->is_home)\n<nav class=\"breadcrumbs\" aria-label=\"breadcrumbs\">\n    <div class=\"wrap\">\n        <a href=\"{{ url($locale ?? \'en\') }}\">{{ ($locale ?? \'\') === \'ru\' ? \'Главная\' : \'Home\' }}</a>\n        @foreach ($page->ancestorsTrail() as $crumb)\n            <span class=\"sep\">/</span>\n            @if ($loop->last)\n                <span class=\"current\">{{ $crumb->tr(\'title\') }}</span>\n            @else\n                <a href=\"{{ $crumb->url }}\">{{ $crumb->tr(\'title\') }}</a>\n            @endif\n        @endforeach\n    </div>\n</nav>\n@endif','2026-06-16 17:54:43');

/*Table structure for table `translations` */

DROP TABLE IF EXISTS `translations`;

CREATE TABLE `translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locale` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `translations_group_key_locale_unique` (`group`,`key`,`locale`),
  KEY `translations_locale_group_index` (`locale`,`group`)
) ENGINE=InnoDB AUTO_INCREMENT=531 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `translations` */

insert  into `translations`(`id`,`group`,`key`,`locale`,`value`,`created_at`) values 
(1,'admin','nav.dashboard','ru','Главная','2026-06-14 15:53:00'),
(2,'admin','nav.documentation','ru','Документация','2026-06-14 15:53:00'),
(3,'admin','nav.message_queue','ru','Очередь сообщений','2026-06-14 15:53:00'),
(4,'admin','queue.model','ru','Сообщение','2026-06-14 15:53:00'),
(5,'admin','queue.model_plural','ru','Сообщения','2026-06-14 15:53:00'),
(6,'admin','queue.col.id','ru','ID','2026-06-14 15:53:00'),
(7,'admin','queue.col.channel','ru','Канал','2026-06-14 15:53:00'),
(8,'admin','queue.col.body','ru','Сообщение','2026-06-14 15:53:00'),
(9,'admin','queue.col.status','ru','Статус','2026-06-14 15:53:00'),
(10,'admin','queue.col.created_at','ru','Создано','2026-06-14 15:53:00'),
(11,'admin','queue.filter.status','ru','Статус обработки','2026-06-14 15:53:00'),
(12,'admin','queue.filter.channel','ru','Канал','2026-06-14 15:53:00'),
(13,'admin','queue.status.pending','ru','Не обработано','2026-06-14 15:53:00'),
(14,'admin','queue.status.processed','ru','Обработано','2026-06-14 15:53:00'),
(15,'admin','stats.total','ru','Всего сообщений','2026-06-14 15:53:00'),
(16,'admin','stats.pending','ru','Не обработано','2026-06-14 15:53:00'),
(17,'admin','stats.processed','ru','Обработано','2026-06-14 15:53:00'),
(18,'admin','users.model','ru','Пользователь','2026-06-14 15:53:00'),
(19,'admin','users.model_plural','ru','Пользователи','2026-06-14 15:53:00'),
(20,'admin','users.nav','ru','Пользователи','2026-06-14 15:53:00'),
(21,'admin','users.col.id','ru','ID','2026-06-14 15:53:00'),
(22,'admin','users.col.login','ru','Логин','2026-06-14 15:53:00'),
(23,'admin','users.col.active','ru','Активен','2026-06-14 15:53:00'),
(24,'admin','users.col.attempts','ru','Попыток','2026-06-14 15:53:00'),
(25,'admin','users.col.super_user','ru','Супер','2026-06-14 15:53:00'),
(26,'admin','users.col.roles','ru','Роли','2026-06-14 15:53:00'),
(27,'admin','users.col.created_at','ru','Создан','2026-06-14 15:53:00'),
(28,'admin','users.col.last_login_at','ru','Последний вход','2026-06-14 15:53:00'),
(29,'admin','users.field.super_user','ru','Суперпользователь','2026-06-14 15:53:00'),
(30,'admin','users.field.super_user_hint','ru','Полный доступ ко всему, в обход ролей и прав','2026-06-14 15:53:00'),
(31,'admin','users.field.login','ru','Логин','2026-06-14 15:53:00'),
(32,'admin','users.field.name','ru','Имя','2026-06-14 15:53:00'),
(33,'admin','users.col.name','ru','Имя','2026-06-14 15:53:00'),
(34,'admin','users.field.password','ru','Пароль','2026-06-14 15:53:00'),
(35,'admin','users.field.password_hint','ru','Оставьте пустым, чтобы не менять','2026-06-14 15:53:00'),
(36,'admin','users.field.active','ru','Активен','2026-06-14 15:53:00'),
(37,'admin','users.field.attempts','ru','Неудачных попыток','2026-06-14 15:53:00'),
(38,'admin','users.field.attempts_hint','ru','Сбросьте в 0 чтобы разблокировать','2026-06-14 15:53:00'),
(39,'admin','users.action.add','ru','Добавить пользователя','2026-06-14 15:53:00'),
(40,'admin','users.action.unlock','ru','Разблокировать','2026-06-14 15:53:00'),
(41,'admin','roles.model','ru','Роль','2026-06-14 15:53:00'),
(42,'admin','roles.model_plural','ru','Роли','2026-06-14 15:53:00'),
(43,'admin','roles.nav','ru','Роли','2026-06-14 15:53:00'),
(44,'admin','roles.col.id','ru','ID','2026-06-14 15:53:00'),
(45,'admin','roles.col.name','ru','Название','2026-06-14 15:53:00'),
(46,'admin','roles.col.description','ru','Описание','2026-06-14 15:53:00'),
(47,'admin','roles.col.users','ru','Пользователей','2026-06-14 15:53:00'),
(48,'admin','roles.col.permissions','ru','Прав','2026-06-14 15:53:00'),
(49,'admin','roles.col.created_at','ru','Создана','2026-06-14 15:53:00'),
(50,'admin','roles.section.main','ru','Основное','2026-06-14 15:53:00'),
(51,'admin','roles.field.name','ru','Название','2026-06-14 15:53:00'),
(52,'admin','roles.field.description','ru','Описание','2026-06-14 15:53:00'),
(53,'admin','roles.field.users','ru','Пользователи','2026-06-14 15:53:00'),
(54,'admin','roles.field.users_hint','ru','Выберите пользователей, которым назначена эта роль','2026-06-14 15:53:00'),
(55,'admin','roles.action.add','ru','Добавить роль','2026-06-14 15:53:00'),
(56,'admin','cms.pages.model','ru','Страница','2026-06-14 15:53:00'),
(57,'admin','cms.pages.model_plural','ru','Страницы','2026-06-14 15:53:00'),
(58,'admin','cms.pages.nav','ru','Страницы','2026-06-14 15:53:00'),
(59,'admin','cms.pages.section.main','ru','Основное','2026-06-14 15:53:00'),
(60,'admin','cms.pages.section.content','ru','Содержимое','2026-06-14 15:53:00'),
(61,'admin','cms.pages.tab.page','ru','Страница','2026-06-14 15:53:00'),
(62,'admin','cms.pages.tab.seo','ru','SEO','2026-06-14 15:53:00'),
(63,'admin','cms.pages.field.meta_title','ru','Meta title','2026-06-14 15:53:00'),
(64,'admin','cms.pages.field.meta_title_hint','ru','Заголовок для <title>; если пусто — берётся заголовок страницы','2026-06-14 15:53:00'),
(65,'admin','cms.pages.field.meta_keywords','ru','Meta keywords','2026-06-14 15:53:00'),
(66,'admin','cms.pages.field.meta_keywords_hint','ru','Ключевые слова через запятую','2026-06-14 15:53:00'),
(67,'admin','cms.pages.field.meta_description','ru','Meta description','2026-06-14 15:53:00'),
(68,'admin','cms.pages.field.meta_description_hint','ru','Краткое описание страницы для поисковиков','2026-06-14 15:53:00'),
(69,'admin','cms.pages.field.parent','ru','Родительская страница','2026-06-14 15:53:00'),
(70,'admin','cms.pages.field.parent_root','ru','Корень (без родителя)','2026-06-14 15:53:00'),
(71,'admin','cms.pages.field.title','ru','Заголовок','2026-06-14 15:53:00'),
(72,'admin','cms.pages.field.slug','ru','URL (slug)','2026-06-14 15:53:00'),
(73,'admin','cms.pages.field.slug_hint','ru','Уникальный адрес страницы, напр. about, services/web','2026-06-14 15:53:00'),
(74,'admin','cms.pages.field.active','ru','Активна','2026-06-14 15:53:00'),
(75,'admin','cms.pages.field.home','ru','Главная','2026-06-14 15:53:00'),
(76,'admin','cms.pages.field.home_hint','ru','Главная страница лендинга (может быть только одна)','2026-06-14 15:53:00'),
(77,'admin','cms.pages.field.sort','ru','Порядок','2026-06-14 15:53:00'),
(78,'admin','cms.pages.field.content','ru','Содержимое','2026-06-14 15:53:00'),
(79,'admin','cms.pages.col.title','ru','Заголовок','2026-06-14 15:53:00'),
(80,'admin','cms.pages.col.slug','ru','URL','2026-06-14 15:53:00'),
(81,'admin','cms.pages.col.home','ru','Главная','2026-06-14 15:53:00'),
(82,'admin','cms.pages.col.active','ru','Активна','2026-06-14 15:53:00'),
(83,'admin','cms.pages.col.sort','ru','Порядок','2026-06-14 15:53:00'),
(84,'admin','cms.pages.col.created_at','ru','Создана','2026-06-14 15:53:00'),
(85,'admin','cms.pages.action.add','ru','Добавить страницу','2026-06-14 15:53:00'),
(86,'admin','cms.pages.action.open','ru','Открыть на сайте','2026-06-14 15:53:00'),
(87,'admin','cms.pages.field.template','ru','Шаблон','2026-06-14 15:53:00'),
(88,'admin','cms.pages.field.template_default','ru','По умолчанию','2026-06-14 15:53:00'),
(89,'admin','cms.pages.field.template_hint','ru','Шаблон вывода страницы; если не выбран — встроенный','2026-06-14 15:53:00'),
(90,'admin','cms.pages.col.template','ru','Шаблон','2026-06-14 15:53:00'),
(91,'admin','cms.templates.model','ru','Шаблон','2026-06-14 15:53:00'),
(92,'admin','cms.templates.model_plural','ru','Шаблоны','2026-06-14 15:53:00'),
(93,'admin','cms.templates.nav','ru','Шаблоны','2026-06-14 15:53:00'),
(94,'admin','cms.templates.section.main','ru','Основное','2026-06-14 15:53:00'),
(95,'admin','cms.templates.section.body','ru','Код шаблона','2026-06-14 15:53:00'),
(96,'admin','cms.templates.field.name','ru','Название','2026-06-14 15:53:00'),
(97,'admin','cms.templates.field.slug','ru','Идентификатор (slug)','2026-06-14 15:53:00'),
(98,'admin','cms.templates.field.body','ru','Разметка (Blade)','2026-06-14 15:53:00'),
(99,'admin','cms.templates.vars.heading','ru','Доступные переменные','2026-06-14 15:53:00'),
(100,'admin','cms.templates.vars.title','ru','заголовок','2026-06-14 15:53:00'),
(101,'admin','cms.templates.vars.content','ru','тело страницы','2026-06-14 15:53:00'),
(102,'admin','cms.templates.vars.children','ru','дочерние','2026-06-14 15:53:00'),
(103,'admin','cms.templates.vars.page','ru','модель страницы','2026-06-14 15:53:00'),
(104,'admin','cms.templates.vars.appname','ru','имя приложения','2026-06-14 15:53:00'),
(105,'admin','cms.templates.vars.output','ru','Вывод тела страницы','2026-06-14 15:53:00'),
(106,'admin','cms.templates.vars.include','ru','Инклюд шаблона','2026-06-14 15:53:00'),
(107,'admin','cms.templates.vars.block','ru','Текстовый блок','2026-06-14 15:53:00'),
(108,'admin','cms.blocks.model','ru','Блок','2026-06-14 15:53:00'),
(109,'admin','cms.blocks.model_plural','ru','Блоки','2026-06-14 15:53:00'),
(110,'admin','cms.blocks.nav','ru','Блоки','2026-06-14 15:53:00'),
(111,'admin','cms.blocks.section.main','ru','Основное','2026-06-14 15:53:00'),
(112,'admin','cms.blocks.field.name','ru','Название','2026-06-14 15:53:00'),
(113,'admin','cms.blocks.field.slug','ru','Идентификатор (slug)','2026-06-14 15:53:00'),
(114,'admin','cms.blocks.field.slug_hint','ru','Вывод в шаблоне: @block(\'slug\')','2026-06-14 15:53:00'),
(115,'admin','cms.blocks.field.value','ru','Значение','2026-06-14 15:53:00'),
(116,'admin','cms.blocks.col.id','ru','ID','2026-06-14 15:53:00'),
(117,'admin','cms.blocks.col.name','ru','Название','2026-06-14 15:53:00'),
(118,'admin','cms.blocks.col.slug','ru','Вставка','2026-06-14 15:53:00'),
(119,'admin','cms.blocks.col.value','ru','Значение','2026-06-14 15:53:00'),
(120,'admin','cms.blocks.col.created_at','ru','Создан','2026-06-14 15:53:00'),
(121,'admin','cms.blocks.action.add','ru','Добавить блок','2026-06-14 15:53:00'),
(122,'admin','cms.templates.field.system','ru','Системный','2026-06-14 15:53:00'),
(123,'admin','cms.templates.field.system_hint','ru','Частичный шаблон (header/footer/menu); защищён от удаления','2026-06-14 15:53:00'),
(124,'admin','cms.templates.field.default','ru','По умолчанию','2026-06-14 15:53:00'),
(125,'admin','cms.templates.field.default_hint','ru','Дефолтный шаблон страниц; защищён от удаления','2026-06-14 15:53:00'),
(126,'admin','cms.templates.col.default','ru','По умолчанию','2026-06-14 15:53:00'),
(127,'admin','cms.templates.col.id','ru','ID','2026-06-14 15:53:00'),
(128,'admin','cms.templates.col.name','ru','Название','2026-06-14 15:53:00'),
(129,'admin','cms.templates.col.slug','ru','Slug','2026-06-14 15:53:00'),
(130,'admin','cms.templates.col.system','ru','Системный','2026-06-14 15:53:00'),
(131,'admin','cms.templates.col.pages','ru','Страниц','2026-06-14 15:53:00'),
(132,'admin','cms.templates.col.created_at','ru','Создан','2026-06-14 15:53:00'),
(133,'admin','cms.templates.action.add','ru','Добавить шаблон','2026-06-14 15:53:00'),
(134,'admin','cms.templates.action.clone','ru','Клонировать','2026-06-14 15:53:00'),
(135,'admin','cms.templates.tab.all','ru','Все','2026-06-14 15:53:00'),
(136,'admin','cms.templates.tab.pages','ru','Шаблоны страниц','2026-06-14 15:53:00'),
(137,'admin','cms.templates.tab.system','ru','Системные','2026-06-14 15:53:00'),
(138,'admin','cms.templates.filter.type','ru','Тип','2026-06-14 15:53:00'),
(139,'admin','firewall.model','ru','Правило файрвола','2026-06-14 15:53:00'),
(140,'admin','firewall.model_plural','ru','Файрвол','2026-06-14 15:53:00'),
(141,'admin','firewall.nav','ru','Файрвол','2026-06-14 15:53:00'),
(142,'admin','firewall.col.id','ru','ID','2026-06-14 15:53:00'),
(143,'admin','firewall.col.ip','ru','IP-адрес','2026-06-14 15:53:00'),
(144,'admin','firewall.col.description','ru','Описание','2026-06-14 15:53:00'),
(145,'admin','firewall.col.active','ru','Активно','2026-06-14 15:53:00'),
(146,'admin','firewall.col.created_at','ru','Создано','2026-06-14 15:53:00'),
(147,'admin','firewall.field.ip','ru','IP-адрес или подсеть','2026-06-14 15:53:00'),
(148,'admin','firewall.field.ip_hint','ru','Один IP (192.168.1.10) или CIDR-подсеть (10.0.0.0/24). IPv6 поддерживается.','2026-06-14 15:53:00'),
(149,'admin','firewall.field.description','ru','Описание','2026-06-14 15:53:00'),
(150,'admin','firewall.field.active','ru','Активно','2026-06-14 15:53:00'),
(151,'admin','firewall.action.add','ru','Добавить правило','2026-06-14 15:53:00'),
(152,'admin','firewall.invalid','ru','Некорректный IP-адрес или CIDR-подсеть.','2026-06-14 15:53:00'),
(153,'admin','firewall.denied','ru','Доступ запрещён: ваш IP-адрес не в списке разрешённых.','2026-06-14 15:53:00'),
(154,'admin','actions.model','ru','Действие','2026-06-14 15:53:00'),
(155,'admin','actions.model_plural','ru','Журнал действий','2026-06-14 15:53:00'),
(156,'admin','actions.nav','ru','Журнал действий','2026-06-14 15:53:00'),
(157,'admin','actions.col.created_at','ru','Время','2026-06-14 15:53:00'),
(158,'admin','actions.col.user','ru','Пользователь','2026-06-14 15:53:00'),
(159,'admin','actions.col.event','ru','Действие','2026-06-14 15:53:00'),
(160,'admin','actions.col.subject','ru','Раздел','2026-06-14 15:53:00'),
(161,'admin','actions.col.subject_id','ru','ID записи','2026-06-14 15:53:00'),
(162,'admin','actions.col.details','ru','Детали','2026-06-14 15:53:00'),
(163,'admin','actions.col.ip','ru','IP-адрес','2026-06-14 15:53:00'),
(164,'admin','actions.event.created','ru','Создание','2026-06-14 15:53:00'),
(165,'admin','actions.event.updated','ru','Изменение','2026-06-14 15:53:00'),
(166,'admin','actions.event.deleted','ru','Удаление','2026-06-14 15:53:00'),
(167,'admin','actions.event.login','ru','Вход','2026-06-14 15:53:00'),
(168,'admin','permissions.heading','ru','Права доступа','2026-06-14 15:53:00'),
(169,'admin','permissions.hint','ru','Отметьте права по модулям и ресурсам админ-панели','2026-06-14 15:53:00'),
(170,'admin','permissions.ability.view','ru','Просмотр','2026-06-14 15:53:00'),
(171,'admin','permissions.ability.create','ru','Создание','2026-06-14 15:53:00'),
(172,'admin','permissions.ability.update','ru','Изменение','2026-06-14 15:53:00'),
(173,'admin','permissions.ability.delete','ru','Удаление','2026-06-14 15:53:00'),
(174,'admin','profile.locale','ru','Язык интерфейса','2026-06-14 15:53:00'),
(175,'admin','profile.timezone','ru','Часовой пояс','2026-06-14 15:53:00'),
(176,'admin','notfound.title','ru','Страница не найдена','2026-06-14 15:53:00'),
(177,'admin','notfound.text','ru','Возможно, она была перемещена, удалена или никогда не существовала.','2026-06-14 15:53:00'),
(178,'admin','notfound.home','ru','Вернуться на главную','2026-06-14 15:53:00'),
(179,'admin','translations.model','ru','Перевод','2026-06-14 15:53:00'),
(180,'admin','translations.model_plural','ru','Переводы','2026-06-14 15:53:00'),
(181,'admin','translations.nav','ru','Переводы','2026-06-14 15:53:00'),
(182,'admin','translations.col.id','ru','ID','2026-06-14 15:53:00'),
(183,'admin','translations.col.group','ru','Группа','2026-06-14 15:53:00'),
(184,'admin','translations.col.key','ru','Ключ','2026-06-14 15:53:00'),
(185,'admin','translations.col.locale','ru','Локаль','2026-06-14 15:53:00'),
(186,'admin','translations.col.value','ru','Значение','2026-06-14 15:53:00'),
(187,'admin','translations.field.group','ru','Группа','2026-06-14 15:53:00'),
(188,'admin','translations.field.key','ru','Ключ','2026-06-14 15:53:00'),
(189,'admin','translations.field.locale','ru','Локаль','2026-06-14 15:53:00'),
(190,'admin','translations.field.value','ru','Значение','2026-06-14 15:53:00'),
(191,'admin','translations.filter.locale','ru','Локаль','2026-06-14 15:53:00'),
(192,'admin','translations.filter.group','ru','Группа','2026-06-14 15:53:00'),
(193,'admin','nav.dashboard','en','Dashboard','2026-06-14 15:53:00'),
(194,'admin','nav.documentation','en','Documentation','2026-06-14 15:53:00'),
(195,'admin','nav.message_queue','en','Message Queue','2026-06-14 15:53:00'),
(196,'admin','queue.model','en','Message','2026-06-14 15:53:00'),
(197,'admin','queue.model_plural','en','Messages','2026-06-14 15:53:00'),
(198,'admin','queue.col.id','en','ID','2026-06-14 15:53:00'),
(199,'admin','queue.col.channel','en','Channel','2026-06-14 15:53:00'),
(200,'admin','queue.col.body','en','Message','2026-06-14 15:53:00'),
(201,'admin','queue.col.status','en','Status','2026-06-14 15:53:00'),
(202,'admin','queue.col.created_at','en','Created','2026-06-14 15:53:00'),
(203,'admin','queue.filter.status','en','Processing status','2026-06-14 15:53:00'),
(204,'admin','queue.filter.channel','en','Channel','2026-06-14 15:53:00'),
(205,'admin','queue.status.pending','en','Pending','2026-06-14 15:53:00'),
(206,'admin','queue.status.processed','en','Processed','2026-06-14 15:53:00'),
(207,'admin','stats.total','en','Total messages','2026-06-14 15:53:00'),
(208,'admin','stats.pending','en','Pending','2026-06-14 15:53:00'),
(209,'admin','stats.processed','en','Processed','2026-06-14 15:53:00'),
(210,'admin','users.model','en','User','2026-06-14 15:53:00'),
(211,'admin','users.model_plural','en','Users','2026-06-14 15:53:00'),
(212,'admin','users.nav','en','Users','2026-06-14 15:53:00'),
(213,'admin','users.col.id','en','ID','2026-06-14 15:53:00'),
(214,'admin','users.col.login','en','Login','2026-06-14 15:53:00'),
(215,'admin','users.col.active','en','Active','2026-06-14 15:53:00'),
(216,'admin','users.col.attempts','en','Attempts','2026-06-14 15:53:00'),
(217,'admin','users.col.super_user','en','Super','2026-06-14 15:53:00'),
(218,'admin','users.col.roles','en','Roles','2026-06-14 15:53:00'),
(219,'admin','users.col.created_at','en','Created','2026-06-14 15:53:00'),
(220,'admin','users.col.last_login_at','en','Last login','2026-06-14 15:53:00'),
(221,'admin','users.field.super_user','en','Super user','2026-06-14 15:53:00'),
(222,'admin','users.field.super_user_hint','en','Full access to everything, bypassing roles and permissions','2026-06-14 15:53:00'),
(223,'admin','users.field.login','en','Login','2026-06-14 15:53:00'),
(224,'admin','users.field.name','en','Name','2026-06-14 15:53:00'),
(225,'admin','users.col.name','en','Name','2026-06-14 15:53:00'),
(226,'admin','users.field.password','en','Password','2026-06-14 15:53:00'),
(227,'admin','users.field.password_hint','en','Leave blank to keep current password','2026-06-14 15:53:00'),
(228,'admin','users.field.active','en','Active','2026-06-14 15:53:00'),
(229,'admin','users.field.attempts','en','Failed attempts','2026-06-14 15:53:00'),
(230,'admin','users.field.attempts_hint','en','Reset to 0 to unblock the account','2026-06-14 15:53:00'),
(231,'admin','users.action.add','en','Add user','2026-06-14 15:53:00'),
(232,'admin','users.action.unlock','en','Unblock','2026-06-14 15:53:00'),
(233,'admin','roles.model','en','Role','2026-06-14 15:53:00'),
(234,'admin','roles.model_plural','en','Roles','2026-06-14 15:53:00'),
(235,'admin','roles.nav','en','Roles','2026-06-14 15:53:00'),
(236,'admin','roles.col.id','en','ID','2026-06-14 15:53:00'),
(237,'admin','roles.col.name','en','Name','2026-06-14 15:53:00'),
(238,'admin','roles.col.description','en','Description','2026-06-14 15:53:00'),
(239,'admin','roles.col.users','en','Users','2026-06-14 15:53:00'),
(240,'admin','roles.col.permissions','en','Perms','2026-06-14 15:53:00'),
(241,'admin','roles.col.created_at','en','Created','2026-06-14 15:53:00'),
(242,'admin','roles.section.main','en','General','2026-06-14 15:53:00'),
(243,'admin','roles.field.name','en','Name','2026-06-14 15:53:00'),
(244,'admin','roles.field.description','en','Description','2026-06-14 15:53:00'),
(245,'admin','roles.field.users','en','Users','2026-06-14 15:53:00'),
(246,'admin','roles.field.users_hint','en','Select the users assigned to this role','2026-06-14 15:53:00'),
(247,'admin','roles.action.add','en','Add role','2026-06-14 15:53:00'),
(248,'admin','cms.pages.model','en','Page','2026-06-14 15:53:00'),
(249,'admin','cms.pages.model_plural','en','Pages','2026-06-14 15:53:00'),
(250,'admin','cms.pages.nav','en','Pages','2026-06-14 15:53:00'),
(251,'admin','cms.pages.section.main','en','General','2026-06-14 15:53:00'),
(252,'admin','cms.pages.section.content','en','Content','2026-06-14 15:53:00'),
(253,'admin','cms.pages.tab.page','en','Page','2026-06-14 15:53:00'),
(254,'admin','cms.pages.tab.seo','en','SEO','2026-06-14 15:53:00'),
(255,'admin','cms.pages.field.meta_title','en','Meta title','2026-06-14 15:53:00'),
(256,'admin','cms.pages.field.meta_title_hint','en','Title for <title>; falls back to the page title if empty','2026-06-14 15:53:00'),
(257,'admin','cms.pages.field.meta_keywords','en','Meta keywords','2026-06-14 15:53:00'),
(258,'admin','cms.pages.field.meta_keywords_hint','en','Comma-separated keywords','2026-06-14 15:53:00'),
(259,'admin','cms.pages.field.meta_description','en','Meta description','2026-06-14 15:53:00'),
(260,'admin','cms.pages.field.meta_description_hint','en','Short page description for search engines','2026-06-14 15:53:00'),
(261,'admin','cms.pages.field.parent','en','Parent page','2026-06-14 15:53:00'),
(262,'admin','cms.pages.field.parent_root','en','Root (no parent)','2026-06-14 15:53:00'),
(263,'admin','cms.pages.field.title','en','Title','2026-06-14 15:53:00'),
(264,'admin','cms.pages.field.slug','en','URL (slug)','2026-06-14 15:53:00'),
(265,'admin','cms.pages.field.slug_hint','en','Unique page address, e.g. about, services/web','2026-06-14 15:53:00'),
(266,'admin','cms.pages.field.active','en','Active','2026-06-14 15:53:00'),
(267,'admin','cms.pages.field.home','en','Home','2026-06-14 15:53:00'),
(268,'admin','cms.pages.field.home_hint','en','Landing home page (only one allowed)','2026-06-14 15:53:00'),
(269,'admin','cms.pages.field.sort','en','Order','2026-06-14 15:53:00'),
(270,'admin','cms.pages.field.content','en','Content','2026-06-14 15:53:00'),
(271,'admin','cms.pages.col.title','en','Title','2026-06-14 15:53:00'),
(272,'admin','cms.pages.col.slug','en','URL','2026-06-14 15:53:00'),
(273,'admin','cms.pages.col.home','en','Home','2026-06-14 15:53:00'),
(274,'admin','cms.pages.col.active','en','Active','2026-06-14 15:53:00'),
(275,'admin','cms.pages.col.sort','en','Order','2026-06-14 15:53:00'),
(276,'admin','cms.pages.col.created_at','en','Created','2026-06-14 15:53:00'),
(277,'admin','cms.pages.action.add','en','Add page','2026-06-14 15:53:00'),
(278,'admin','cms.pages.action.open','en','Open on site','2026-06-14 15:53:00'),
(279,'admin','cms.pages.field.template','en','Template','2026-06-14 15:53:00'),
(280,'admin','cms.pages.field.template_default','en','Default','2026-06-14 15:53:00'),
(281,'admin','cms.pages.field.template_hint','en','Page output template; built-in is used if none selected','2026-06-14 15:53:00'),
(282,'admin','cms.pages.col.template','en','Template','2026-06-14 15:53:00'),
(283,'admin','cms.templates.model','en','Template','2026-06-14 15:53:00'),
(284,'admin','cms.templates.model_plural','en','Templates','2026-06-14 15:53:00'),
(285,'admin','cms.templates.nav','en','Templates','2026-06-14 15:53:00'),
(286,'admin','cms.templates.section.main','en','General','2026-06-14 15:53:00'),
(287,'admin','cms.templates.section.body','en','Template code','2026-06-14 15:53:00'),
(288,'admin','cms.templates.field.name','en','Name','2026-06-14 15:53:00'),
(289,'admin','cms.templates.field.slug','en','Identifier (slug)','2026-06-14 15:53:00'),
(290,'admin','cms.templates.field.body','en','Markup (Blade)','2026-06-14 15:53:00'),
(291,'admin','cms.templates.vars.heading','en','Available variables','2026-06-14 15:53:00'),
(292,'admin','cms.templates.vars.title','en','title','2026-06-14 15:53:00'),
(293,'admin','cms.templates.vars.content','en','page body','2026-06-14 15:53:00'),
(294,'admin','cms.templates.vars.children','en','children','2026-06-14 15:53:00'),
(295,'admin','cms.templates.vars.page','en','page model','2026-06-14 15:53:00'),
(296,'admin','cms.templates.vars.appname','en','app name','2026-06-14 15:53:00'),
(297,'admin','cms.templates.vars.output','en','Output page body','2026-06-14 15:53:00'),
(298,'admin','cms.templates.vars.include','en','Include template','2026-06-14 15:53:00'),
(299,'admin','cms.templates.vars.block','en','Text block','2026-06-14 15:53:00'),
(300,'admin','cms.blocks.model','en','Block','2026-06-14 15:53:00'),
(301,'admin','cms.blocks.model_plural','en','Blocks','2026-06-14 15:53:00'),
(302,'admin','cms.blocks.nav','en','Blocks','2026-06-14 15:53:00'),
(303,'admin','cms.blocks.section.main','en','General','2026-06-14 15:53:00'),
(304,'admin','cms.blocks.field.name','en','Name','2026-06-14 15:53:00'),
(305,'admin','cms.blocks.field.slug','en','Identifier (slug)','2026-06-14 15:53:00'),
(306,'admin','cms.blocks.field.slug_hint','en','Output in template: @block(\'slug\')','2026-06-14 15:53:00'),
(307,'admin','cms.blocks.field.value','en','Value','2026-06-14 15:53:00'),
(308,'admin','cms.blocks.col.id','en','ID','2026-06-14 15:53:00'),
(309,'admin','cms.blocks.col.name','en','Name','2026-06-14 15:53:00'),
(310,'admin','cms.blocks.col.slug','en','Insert','2026-06-14 15:53:00'),
(311,'admin','cms.blocks.col.value','en','Value','2026-06-14 15:53:00'),
(312,'admin','cms.blocks.col.created_at','en','Created','2026-06-14 15:53:00'),
(313,'admin','cms.blocks.action.add','en','Add block','2026-06-14 15:53:00'),
(314,'admin','cms.templates.field.system','en','System','2026-06-14 15:53:00'),
(315,'admin','cms.templates.field.system_hint','en','Partial template (header/footer/menu); protected from deletion','2026-06-14 15:53:00'),
(316,'admin','cms.templates.field.default','en','Default','2026-06-14 15:53:00'),
(317,'admin','cms.templates.field.default_hint','en','Default page template; protected from deletion','2026-06-14 15:53:00'),
(318,'admin','cms.templates.col.default','en','Default','2026-06-14 15:53:00'),
(319,'admin','cms.templates.col.id','en','ID','2026-06-14 15:53:00'),
(320,'admin','cms.templates.col.name','en','Name','2026-06-14 15:53:00'),
(321,'admin','cms.templates.col.slug','en','Slug','2026-06-14 15:53:00'),
(322,'admin','cms.templates.col.system','en','System','2026-06-14 15:53:00'),
(323,'admin','cms.templates.col.pages','en','Pages','2026-06-14 15:53:00'),
(324,'admin','cms.templates.col.created_at','en','Created','2026-06-14 15:53:00'),
(325,'admin','cms.templates.action.add','en','Add template','2026-06-14 15:53:00'),
(326,'admin','cms.templates.action.clone','en','Clone','2026-06-14 15:53:00'),
(327,'admin','cms.templates.tab.all','en','All','2026-06-14 15:53:00'),
(328,'admin','cms.templates.tab.pages','en','Page templates','2026-06-14 15:53:00'),
(329,'admin','cms.templates.tab.system','en','System','2026-06-14 15:53:00'),
(330,'admin','cms.templates.filter.type','en','Type','2026-06-14 15:53:00'),
(331,'admin','firewall.model','en','Firewall rule','2026-06-14 15:53:00'),
(332,'admin','firewall.model_plural','en','Firewall','2026-06-14 15:53:00'),
(333,'admin','firewall.nav','en','Firewall','2026-06-14 15:53:00'),
(334,'admin','firewall.col.id','en','ID','2026-06-14 15:53:00'),
(335,'admin','firewall.col.ip','en','IP address','2026-06-14 15:53:00'),
(336,'admin','firewall.col.description','en','Description','2026-06-14 15:53:00'),
(337,'admin','firewall.col.active','en','Active','2026-06-14 15:53:00'),
(338,'admin','firewall.col.created_at','en','Created','2026-06-14 15:53:00'),
(339,'admin','firewall.field.ip','en','IP address or subnet','2026-06-14 15:53:00'),
(340,'admin','firewall.field.ip_hint','en','Single IP (192.168.1.10) or CIDR subnet (10.0.0.0/24). IPv6 supported.','2026-06-14 15:53:00'),
(341,'admin','firewall.field.description','en','Description','2026-06-14 15:53:00'),
(342,'admin','firewall.field.active','en','Active','2026-06-14 15:53:00'),
(343,'admin','firewall.action.add','en','Add rule','2026-06-14 15:53:00'),
(344,'admin','firewall.invalid','en','Invalid IP address or CIDR subnet.','2026-06-14 15:53:00'),
(345,'admin','firewall.denied','en','Access denied: your IP address is not allowed.','2026-06-14 15:53:00'),
(346,'admin','actions.model','en','Action','2026-06-14 15:53:00'),
(347,'admin','actions.model_plural','en','Action log','2026-06-14 15:53:00'),
(348,'admin','actions.nav','en','Actions','2026-06-14 15:53:00'),
(349,'admin','actions.col.created_at','en','Time','2026-06-14 15:53:00'),
(350,'admin','actions.col.user','en','User','2026-06-14 15:53:00'),
(351,'admin','actions.col.event','en','Action','2026-06-14 15:53:00'),
(352,'admin','actions.col.subject','en','Section','2026-06-14 15:53:00'),
(353,'admin','actions.col.subject_id','en','Record ID','2026-06-14 15:53:00'),
(354,'admin','actions.col.details','en','Details','2026-06-14 15:53:00'),
(355,'admin','actions.col.ip','en','IP address','2026-06-14 15:53:00'),
(356,'admin','actions.event.created','en','Created','2026-06-14 15:53:00'),
(357,'admin','actions.event.updated','en','Updated','2026-06-14 15:53:00'),
(358,'admin','actions.event.deleted','en','Deleted','2026-06-14 15:53:00'),
(359,'admin','actions.event.login','en','Login','2026-06-14 15:53:00'),
(360,'admin','permissions.heading','en','Permissions','2026-06-14 15:53:00'),
(361,'admin','permissions.hint','en','Select permissions per module and admin panel resource','2026-06-14 15:53:00'),
(362,'admin','permissions.ability.view','en','View','2026-06-14 15:53:00'),
(363,'admin','permissions.ability.create','en','Create','2026-06-14 15:53:00'),
(364,'admin','permissions.ability.update','en','Update','2026-06-14 15:53:00'),
(365,'admin','permissions.ability.delete','en','Delete','2026-06-14 15:53:00'),
(366,'admin','profile.locale','en','Interface language','2026-06-14 15:53:00'),
(367,'admin','profile.timezone','en','Timezone','2026-06-14 15:53:00'),
(368,'admin','notfound.title','en','Page not found','2026-06-14 15:53:00'),
(369,'admin','notfound.text','en','It may have been moved, deleted, or never existed.','2026-06-14 15:53:00'),
(370,'admin','notfound.home','en','Back to home','2026-06-14 15:53:00'),
(371,'admin','translations.model','en','Translation','2026-06-14 15:53:00'),
(372,'admin','translations.model_plural','en','Translations','2026-06-14 15:53:00'),
(373,'admin','translations.nav','en','Translations','2026-06-14 15:53:00'),
(374,'admin','translations.col.id','en','ID','2026-06-14 15:53:00'),
(375,'admin','translations.col.group','en','Group','2026-06-14 15:53:00'),
(376,'admin','translations.col.key','en','Key','2026-06-14 15:53:00'),
(377,'admin','translations.col.locale','en','Locale','2026-06-14 15:53:00'),
(378,'admin','translations.col.value','en','Value','2026-06-14 15:53:00'),
(379,'admin','translations.field.group','en','Group','2026-06-14 15:53:00'),
(380,'admin','translations.field.key','en','Key','2026-06-14 15:53:00'),
(381,'admin','translations.field.locale','en','Locale','2026-06-14 15:53:00'),
(382,'admin','translations.field.value','en','Value','2026-06-14 15:53:00'),
(383,'admin','translations.filter.locale','en','Locale','2026-06-14 15:53:00'),
(384,'admin','translations.filter.group','en','Group','2026-06-14 15:53:00'),
(385,'admin','settings.nav','ru','Настройки сайта','2026-06-14 20:26:50'),
(386,'admin','settings.nav','en','Site settings','2026-06-14 20:26:50'),
(387,'admin','settings.title','ru','Настройки сайта','2026-06-14 20:26:50'),
(388,'admin','settings.title','en','Site settings','2026-06-14 20:26:50'),
(389,'admin','settings.save','ru','Сохранить','2026-06-14 20:26:50'),
(390,'admin','settings.save','en','Save','2026-06-14 20:26:50'),
(391,'admin','settings.saved','ru','Настройки сохранены','2026-06-14 20:26:50'),
(392,'admin','settings.saved','en','Settings saved','2026-06-14 20:26:50'),
(393,'admin','settings.tab.general','ru','Общие','2026-06-14 20:26:50'),
(394,'admin','settings.tab.general','en','General','2026-06-14 20:26:50'),
(395,'admin','settings.tab.seo','ru','SEO','2026-06-14 20:26:50'),
(396,'admin','settings.tab.seo','en','SEO','2026-06-14 20:26:50'),
(397,'admin','settings.tab.scripts','ru','Скрипты и аналитика','2026-06-14 20:26:50'),
(398,'admin','settings.tab.scripts','en','Scripts & analytics','2026-06-14 20:26:50'),
(399,'admin','settings.tab.maintenance','ru','Обслуживание','2026-06-14 20:26:50'),
(400,'admin','settings.tab.maintenance','en','Maintenance','2026-06-14 20:26:50'),
(401,'admin','settings.field.site_name','ru','Название сайта','2026-06-14 20:26:50'),
(402,'admin','settings.field.site_name','en','Site name','2026-06-14 20:26:50'),
(403,'admin','settings.field.site_tagline','ru','Слоган','2026-06-14 20:26:50'),
(404,'admin','settings.field.site_tagline','en','Tagline','2026-06-14 20:26:50'),
(405,'admin','settings.field.site_logo','ru','Логотип','2026-06-14 20:26:50'),
(406,'admin','settings.field.site_logo','en','Logo','2026-06-14 20:26:50'),
(407,'admin','settings.field.site_favicon','ru','Favicon','2026-06-14 20:26:50'),
(408,'admin','settings.field.site_favicon','en','Favicon','2026-06-14 20:26:50'),
(409,'admin','settings.field.seo_title_suffix','ru','Суффикс <title>','2026-06-14 20:26:50'),
(410,'admin','settings.field.seo_title_suffix','en','<title> suffix','2026-06-14 20:26:50'),
(411,'admin','settings.field.seo_title_suffix_hint','ru','Добавляется к заголовку вкладки, напр. « — Моя компания»','2026-06-14 20:26:50'),
(412,'admin','settings.field.seo_title_suffix_hint','en','Appended to the page title, e.g. \" — My Company\"','2026-06-14 20:26:50'),
(413,'admin','settings.field.seo_default_keywords','ru','Ключевые слова по умолчанию','2026-06-14 20:26:50'),
(414,'admin','settings.field.seo_default_keywords','en','Default keywords','2026-06-14 20:26:50'),
(415,'admin','settings.field.seo_default_description','ru','Описание по умолчанию','2026-06-14 20:26:50'),
(416,'admin','settings.field.seo_default_description','en','Default description','2026-06-14 20:26:50'),
(417,'admin','settings.field.seo_index','ru','Разрешить индексацию','2026-06-14 20:26:50'),
(418,'admin','settings.field.seo_index','en','Allow indexing','2026-06-14 20:26:50'),
(419,'admin','settings.field.seo_index_hint','ru','Выключите, чтобы закрыть сайт от поисковиков (noindex)','2026-06-14 20:26:50'),
(420,'admin','settings.field.seo_index_hint','en','Turn off to hide the site from search engines (noindex)','2026-06-14 20:26:50'),
(421,'admin','settings.field.analytics_head','ru','Код в <head>','2026-06-14 20:26:50'),
(422,'admin','settings.field.analytics_head','en','Code in <head>','2026-06-14 20:26:50'),
(423,'admin','settings.field.analytics_head_hint','ru','Счётчики/верификации; вставляется в <head>','2026-06-14 20:26:50'),
(424,'admin','settings.field.analytics_head_hint','en','Counters/verifications; injected into <head>','2026-06-14 20:26:50'),
(425,'admin','settings.field.analytics_body','ru','Код перед </body>','2026-06-14 20:26:50'),
(426,'admin','settings.field.analytics_body','en','Code before </body>','2026-06-14 20:26:50'),
(427,'admin','settings.field.analytics_body_hint','ru','Скрипты аналитики; вставляется перед закрытием </body>','2026-06-14 20:26:50'),
(428,'admin','settings.field.analytics_body_hint','en','Analytics scripts; injected before closing </body>','2026-06-14 20:26:50'),
(429,'admin','settings.field.maintenance_mode','ru','Режим обслуживания','2026-06-14 20:26:50'),
(430,'admin','settings.field.maintenance_mode','en','Maintenance mode','2026-06-14 20:26:50'),
(431,'admin','settings.field.maintenance_mode_hint','ru','Сайт закрыт для посетителей; админы видят сайт как обычно','2026-06-14 20:26:50'),
(432,'admin','settings.field.maintenance_mode_hint','en','Site is closed for visitors; admins still see it normally','2026-06-14 20:26:50'),
(433,'admin','settings.field.maintenance_message','ru','Сообщение на странице обслуживания','2026-06-14 20:26:50'),
(434,'admin','settings.field.maintenance_message','en','Maintenance page message','2026-06-14 20:26:50'),
(435,'admin','settings.field.contact_email','ru','E-mail для уведомлений','2026-06-14 20:26:50'),
(436,'admin','settings.field.contact_email','en','Notification e-mail','2026-06-14 20:26:50'),
(437,'admin','settings.field.contact_email_hint','ru','Например, для заявок с форм','2026-06-14 20:26:50'),
(438,'admin','settings.field.contact_email_hint','en','E.g. for form submissions','2026-06-14 20:26:50'),
(439,'admin','settings.tab.custom','ru','Произвольные','2026-06-14 20:33:30'),
(440,'admin','settings.tab.custom','en','Custom','2026-06-14 20:33:30'),
(441,'admin','settings.custom.hint','ru','Произвольные настройки key → value. В коде: Setting::get(\'key\') или @setting(\'key\') в шаблоне.','2026-06-14 20:33:30'),
(442,'admin','settings.custom.hint','en','Arbitrary key → value settings. In code: Setting::get(\'key\') or @setting(\'key\') in a template.','2026-06-14 20:33:30'),
(443,'admin','settings.custom.add','ru','Добавить настройку','2026-06-14 20:33:30'),
(444,'admin','settings.custom.add','en','Add setting','2026-06-14 20:33:30'),
(445,'admin','settings.field.custom_key','ru','Ключ','2026-06-14 20:33:30'),
(446,'admin','settings.field.custom_key','en','Key','2026-06-14 20:33:30'),
(447,'admin','settings.field.custom_value','ru','Значение','2026-06-14 20:33:30'),
(448,'admin','settings.field.custom_value','en','Value','2026-06-14 20:33:30'),
(449,'admin','dash.overview','ru','Состояние сайта','2026-06-15 17:49:25'),
(450,'admin','dash.overview','en','Site overview','2026-06-15 17:49:25'),
(451,'admin','dash.pages','ru','Страницы','2026-06-15 17:49:25'),
(452,'admin','dash.pages','en','Pages','2026-06-15 17:49:25'),
(453,'admin','dash.templates','ru','Шаблоны','2026-06-15 17:49:25'),
(454,'admin','dash.templates','en','Templates','2026-06-15 17:49:25'),
(455,'admin','dash.blocks','ru','Блоки','2026-06-15 17:49:25'),
(456,'admin','dash.blocks','en','Blocks','2026-06-15 17:49:25'),
(457,'admin','dash.users','ru','Пользователи','2026-06-15 17:49:25'),
(458,'admin','dash.users','en','Users','2026-06-15 17:49:25'),
(459,'admin','dash.translations','ru','Переводы','2026-06-15 17:49:25'),
(460,'admin','dash.translations','en','Translations','2026-06-15 17:49:25'),
(461,'admin','dash.actions','ru','Действий за 7 дней','2026-06-15 17:49:25'),
(462,'admin','dash.actions','en','Actions · 7 days','2026-06-15 17:49:25'),
(463,'admin','dash.maintenance','ru','Режим обслуживания','2026-06-15 17:49:25'),
(464,'admin','dash.maintenance','en','Maintenance','2026-06-15 17:49:25'),
(465,'admin','dash.firewall','ru','Файрвол','2026-06-15 17:49:25'),
(466,'admin','dash.firewall','en','Firewall','2026-06-15 17:49:25'),
(467,'admin','dash.published','ru',':n опубликовано','2026-06-15 17:49:25'),
(468,'admin','dash.published','en',':n published','2026-06-15 17:49:25'),
(469,'admin','dash.active','ru',':n активных','2026-06-15 17:49:25'),
(470,'admin','dash.active','en',':n active','2026-06-15 17:49:25'),
(471,'admin','dash.on','ru','Включён','2026-06-15 17:49:25'),
(472,'admin','dash.on','en','On','2026-06-15 17:49:25'),
(473,'admin','dash.off','ru','Выключен','2026-06-15 17:49:25'),
(474,'admin','dash.off','en','Off','2026-06-15 17:49:25'),
(475,'admin','dash.fw_rules','ru',':n активных правил','2026-06-15 17:49:25'),
(476,'admin','dash.fw_rules','en',':n active rules','2026-06-15 17:49:25'),
(477,'admin','dash.fw_open','ru','Открыт','2026-06-15 17:49:25'),
(478,'admin','dash.fw_open','en','Open','2026-06-15 17:49:25'),
(479,'admin','dash.fw_open_desc','ru','доступ для всех IP','2026-06-15 17:49:25'),
(480,'admin','dash.fw_open_desc','en','open to all IPs','2026-06-15 17:49:25'),
(481,'admin','languages.nav','ru','Языки','2026-06-16 17:45:25'),
(482,'admin','languages.nav','en','Languages','2026-06-16 17:45:25'),
(483,'admin','languages.model','ru','Язык','2026-06-16 17:45:25'),
(484,'admin','languages.model','en','Language','2026-06-16 17:45:25'),
(485,'admin','languages.model_plural','ru','Языки','2026-06-16 17:45:25'),
(486,'admin','languages.model_plural','en','Languages','2026-06-16 17:45:25'),
(487,'admin','languages.field.code','ru','Код','2026-06-16 17:45:25'),
(488,'admin','languages.field.code','en','Code','2026-06-16 17:45:25'),
(489,'admin','languages.field.code_hint','ru','ISO-код локали для URL: /en, /ru','2026-06-16 17:45:25'),
(490,'admin','languages.field.code_hint','en','Locale code used in URLs: /en, /ru','2026-06-16 17:45:25'),
(491,'admin','languages.field.name','ru','Название','2026-06-16 17:45:25'),
(492,'admin','languages.field.name','en','Name','2026-06-16 17:45:25'),
(493,'admin','languages.field.default','ru','По умолчанию','2026-06-16 17:45:25'),
(494,'admin','languages.field.default','en','Default','2026-06-16 17:45:25'),
(495,'admin','languages.field.default_hint','ru','Язык по умолчанию ровно один; на него ведут URL без префикса.','2026-06-16 17:45:25'),
(496,'admin','languages.field.default_hint','en','Exactly one default language; URLs without a prefix redirect to it.','2026-06-16 17:45:25'),
(497,'admin','languages.field.active','ru','Активен','2026-06-16 17:45:25'),
(498,'admin','languages.field.active','en','Active','2026-06-16 17:45:25'),
(499,'admin','languages.field.sort','ru','Порядок','2026-06-16 17:45:25'),
(500,'admin','languages.field.sort','en','Sort order','2026-06-16 17:45:25'),
(501,'admin','languages.col.id','ru','ID','2026-06-16 17:45:25'),
(502,'admin','languages.col.id','en','ID','2026-06-16 17:45:25'),
(503,'admin','languages.col.code','ru','Код','2026-06-16 17:45:25'),
(504,'admin','languages.col.code','en','Code','2026-06-16 17:45:25'),
(505,'admin','languages.col.name','ru','Название','2026-06-16 17:45:25'),
(506,'admin','languages.col.name','en','Name','2026-06-16 17:45:25'),
(507,'admin','languages.col.default','ru','По умолчанию','2026-06-16 17:45:25'),
(508,'admin','languages.col.default','en','Default','2026-06-16 17:45:25'),
(509,'admin','languages.col.active','ru','Активен','2026-06-16 17:45:25'),
(510,'admin','languages.col.active','en','Active','2026-06-16 17:45:25'),
(511,'admin','languages.col.sort','ru','Порядок','2026-06-16 17:45:25'),
(512,'admin','languages.col.sort','en','Sort','2026-06-16 17:45:25'),
(513,'admin','languages.col.created_at','ru','Создан','2026-06-16 17:45:25'),
(514,'admin','languages.col.created_at','en','Created','2026-06-16 17:45:25'),
(515,'admin','languages.action.add','ru','Добавить язык','2026-06-16 17:45:25'),
(516,'admin','languages.action.add','en','Add language','2026-06-16 17:45:25'),
(517,'admin','cms.pages.action.clone','ru','Клонировать','2026-06-16 17:50:46'),
(518,'admin','cms.pages.action.clone','en','Clone','2026-06-16 17:50:46'),
(519,'admin','cms.pages.action.clone_hint','ru','Будет создана копия страницы с новым slug. Дочерние страницы не копируются.','2026-06-16 17:50:46'),
(520,'admin','cms.pages.action.clone_hint','en','A copy of the page with a new slug will be created. Child pages are not cloned.','2026-06-16 17:50:46'),
(521,'admin','cms.pages.clone_suffix','ru','копия','2026-06-16 17:50:46'),
(522,'admin','cms.pages.clone_suffix','en','copy','2026-06-16 17:50:46'),
(523,'admin','cms.pages.clone_done','ru','Страница склонирована','2026-06-16 17:50:46'),
(524,'admin','cms.pages.clone_done','en','Page cloned','2026-06-16 17:50:46'),
(525,'admin','profile.title','ru','Профиль','2026-06-16 18:07:03'),
(526,'admin','profile.title','en','Profile','2026-06-16 18:07:03'),
(527,'admin','profile.nav','ru','Профиль','2026-06-16 18:07:03'),
(528,'admin','profile.nav','en','Profile','2026-06-16 18:07:03'),
(529,'admin','profile.saved','ru','Профиль обновлён','2026-06-16 18:07:03'),
(530,'admin','profile.saved','en','Profile updated','2026-06-16 18:07:03');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `super_user` tinyint(1) NOT NULL DEFAULT '0',
  `failed_attempts` tinyint(4) NOT NULL DEFAULT '0',
  `locale` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ru',
  `timezone` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UTC',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `docs_users_login_unique` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`login`,`name`,`password`,`is_active`,`super_user`,`failed_attempts`,`locale`,`timezone`,`created_at`,`last_login_at`) values 
(1,'admin','Админ','$2y$12$k9xTo/JmNA/cFl18tN7Z3.jSnrQpID51VLnCqmJMZexf4NSzeFW4a',1,1,0,'ru','Europe/Chisinau','2026-06-06 19:44:03','2026-06-16 18:09:31'),
(2,'alex','Александр','$2y$12$KqOIw7LjaQ9ktE0ePK/ehugB3kP16FNY0ivRnwRcCmCosFruxxsg2',1,0,0,'ru','UTC','2026-06-07 17:46:19',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
