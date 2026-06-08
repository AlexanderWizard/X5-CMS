<?php

return [

    // Navigation
    'nav.dashboard'        => 'Dashboard',
    'nav.documentation'    => 'Documentation',
    'nav.message_queue'    => 'Message Queue',

    // MessageQueue resource
    'queue.model'          => 'Message',
    'queue.model_plural'   => 'Messages',
    'queue.col.id'         => 'ID',
    'queue.col.channel'    => 'Channel',
    'queue.col.body'       => 'Message',
    'queue.col.status'     => 'Status',
    'queue.col.created_at' => 'Created',
    'queue.filter.status'  => 'Processing status',
    'queue.filter.channel' => 'Channel',
    'queue.status.pending'    => 'Pending',
    'queue.status.processed'  => 'Processed',

    // Stats widget
    'stats.total'          => 'Total messages',
    'stats.pending'        => 'Pending',
    'stats.processed'      => 'Processed',

    // DocsUser resource
    'users.model'          => 'User',
    'users.model_plural'   => 'Users',
    'users.nav'            => 'Users',
    'users.col.id'         => 'ID',
    'users.col.login'      => 'Login',
    'users.col.active'     => 'Active',
    'users.col.attempts'   => 'Attempts',
    'users.col.created_at'    => 'Created',
    'users.col.last_login_at' => 'Last login',
    'users.field.login'    => 'Login',
    'users.field.password' => 'Password',
    'users.field.password_hint' => 'Leave blank to keep current password',
    'users.field.active'   => 'Active',
    'users.field.attempts' => 'Failed attempts',
    'users.field.attempts_hint' => 'Reset to 0 to unblock the account',
    'users.action.add'     => 'Add user',
    'users.action.unlock'  => 'Unblock',

    // Role resource
    'roles.model'          => 'Role',
    'roles.model_plural'   => 'Roles',
    'roles.nav'            => 'Roles',
    'roles.col.id'         => 'ID',
    'roles.col.name'       => 'Name',
    'roles.col.description' => 'Description',
    'roles.col.users'      => 'Users',
    'roles.col.created_at' => 'Created',
    'roles.field.name'        => 'Name',
    'roles.field.description' => 'Description',
    'roles.field.users'       => 'Users',
    'roles.field.users_hint'  => 'Select the users assigned to this role',
    'roles.action.add'        => 'Add role',

    // Profile
    'profile.locale'       => 'Interface language',
    'profile.timezone'     => 'Timezone',

    // 404
    'notfound.title'       => 'Page not found',
    'notfound.text'        => 'It may have been moved, deleted, or never existed.',
    'notfound.home'        => 'Back to home',

];
