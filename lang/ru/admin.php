<?php

return [

    // Navigation
    'nav.dashboard'        => 'Главная',
    'nav.documentation'    => 'Документация',
    'nav.message_queue'    => 'Очередь сообщений',

    // MessageQueue resource
    'queue.model'          => 'Сообщение',
    'queue.model_plural'   => 'Сообщения',
    'queue.col.id'         => 'ID',
    'queue.col.channel'    => 'Канал',
    'queue.col.body'       => 'Сообщение',
    'queue.col.status'     => 'Статус',
    'queue.col.created_at' => 'Создано',
    'queue.filter.status'  => 'Статус обработки',
    'queue.filter.channel' => 'Канал',
    'queue.status.pending'    => 'Не обработано',
    'queue.status.processed'  => 'Обработано',

    // Stats widget
    'stats.total'          => 'Всего сообщений',
    'stats.pending'        => 'Не обработано',
    'stats.processed'      => 'Обработано',

    // DocsUser resource
    'users.model'          => 'Пользователь',
    'users.model_plural'   => 'Пользователи',
    'users.nav'            => 'Users',
    'users.col.id'         => 'ID',
    'users.col.login'      => 'Логин',
    'users.col.active'     => 'Активен',
    'users.col.attempts'   => 'Попыток',
    'users.col.active'        => 'Активен',
    'users.col.super_user'    => 'Супер',
    'users.col.roles'         => 'Роли',
    'users.col.created_at'    => 'Создан',
    'users.col.last_login_at' => 'Последний вход',
    'users.field.super_user'      => 'Суперпользователь',
    'users.field.super_user_hint' => 'Полный доступ ко всему, в обход ролей и прав',
    'users.field.login'    => 'Логин',
    'users.field.password' => 'Пароль',
    'users.field.password_hint' => 'Оставьте пустым, чтобы не менять',
    'users.field.active'   => 'Активен',
    'users.field.attempts' => 'Неудачных попыток',
    'users.field.attempts_hint' => 'Сбросьте в 0 чтобы разблокировать',
    'users.action.add'     => 'Добавить пользователя',
    'users.action.unlock'  => 'Разблокировать',

    // Role resource
    'roles.model'          => 'Роль',
    'roles.model_plural'   => 'Роли',
    'roles.nav'            => 'Roles',
    'roles.col.id'         => 'ID',
    'roles.col.name'       => 'Название',
    'roles.col.description' => 'Описание',
    'roles.col.users'      => 'Пользователей',
    'roles.col.permissions' => 'Прав',
    'roles.col.created_at' => 'Создана',
    'roles.section.main'      => 'Основное',
    'roles.field.name'        => 'Название',
    'roles.field.description' => 'Описание',
    'roles.field.users'       => 'Пользователи',
    'roles.field.users_hint'  => 'Выберите пользователей, которым назначена эта роль',
    'roles.action.add'        => 'Добавить роль',

    // Firewall resource
    'firewall.model'        => 'Правило файрвола',
    'firewall.model_plural' => 'Файрвол',
    'firewall.nav'          => 'Firewall',
    'firewall.col.id'         => 'ID',
    'firewall.col.ip'         => 'IP-адрес',
    'firewall.col.description' => 'Описание',
    'firewall.col.active'     => 'Активно',
    'firewall.col.created_at' => 'Создано',
    'firewall.field.ip'         => 'IP-адрес или подсеть',
    'firewall.field.ip_hint'    => 'Один IP (192.168.1.10) или CIDR-подсеть (10.0.0.0/24). IPv6 поддерживается.',
    'firewall.field.description' => 'Описание',
    'firewall.field.active'      => 'Активно',
    'firewall.action.add'        => 'Добавить правило',
    'firewall.invalid'           => 'Некорректный IP-адрес или CIDR-подсеть.',
    'firewall.denied'            => 'Доступ запрещён: ваш IP-адрес не в списке разрешённых.',

    // Permissions tree
    'permissions.heading'       => 'Права доступа',
    'permissions.hint'          => 'Отметьте права по модулям и ресурсам админ-панели',
    'permissions.ability.view'   => 'Просмотр',
    'permissions.ability.create' => 'Создание',
    'permissions.ability.update' => 'Изменение',
    'permissions.ability.delete' => 'Удаление',

    // Profile
    'profile.locale'       => 'Язык интерфейса',
    'profile.timezone'     => 'Часовой пояс',

    // 404
    'notfound.title'       => 'Страница не найдена',
    'notfound.text'        => 'Возможно, она была перемещена, удалена или никогда не существовала.',
    'notfound.home'        => 'Вернуться на главную',

];
