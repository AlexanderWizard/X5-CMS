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
    'users.col.created_at'    => 'Создан',
    'users.col.last_login_at' => 'Последний вход',
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
    'roles.col.created_at' => 'Создана',
    'roles.field.name'        => 'Название',
    'roles.field.description' => 'Описание',
    'roles.field.users'       => 'Пользователи',
    'roles.field.users_hint'  => 'Выберите пользователей, которым назначена эта роль',
    'roles.action.add'        => 'Добавить роль',

    // Profile
    'profile.locale'       => 'Язык интерфейса',
    'profile.timezone'     => 'Часовой пояс',

    // 404
    'notfound.title'       => 'Страница не найдена',
    'notfound.text'        => 'Возможно, она была перемещена, удалена или никогда не существовала.',
    'notfound.home'        => 'Вернуться на главную',

];
