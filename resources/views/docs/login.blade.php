<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Документация — Вход</title>
    <link rel="stylesheet" href="{{ asset('css/docs-login.css') }}">
</head>
<body>

    <div class="login-wrapper">

        <div class="login-logo">
            <div class="login-logo__icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="login-logo__title">API Документация</span>
        </div>

        <div class="login-card">

            <h1 class="login-card__heading">Добро пожаловать</h1>
            <p class="login-card__sub">Войдите для доступа к документации</p>

            @if ($errors->any())
                <div class="login-error">
                    <svg class="login-error__icon" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="login-error__text">{{ $errors->first('login') }}</span>
                </div>
            @endif

            <form class="login-form" method="POST" action="{{ route('docs.login.submit') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="login">Логин</label>
                    <input class="form-input" type="text" id="login" name="login"
                           value="{{ old('login') }}" autocomplete="username" autofocus required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Пароль</label>
                    <input class="form-input" type="password" id="password" name="password"
                           autocomplete="current-password" required>
                </div>

                <button class="form-btn" type="submit">Войти</button>

            </form>
        </div>

        <p class="login-footer">notify_service &middot; API Documentation</p>

    </div>

</body>
</html>
