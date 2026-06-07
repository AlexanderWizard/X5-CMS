<?php

namespace App\Filament\Pages\Auth;

use App\Models\DocsUser;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('login')
                    ->label('Логин')
                    ->required()
                    ->autocomplete('username')
                    ->autofocus(),

                TextInput::make('password')
                    ->label('Пароль')
                    ->password()
                    ->revealable()
                    ->required(),
            ]);
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        $user = DocsUser::where('login', $data['login'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'data.login' => 'Неверный логин или пароль.',
            ]);
        }

        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'data.login' => 'Учётная запись заблокирована. Обратитесь к администратору.',
            ]);
        }

        if (!Hash::check($data['password'], $user->password)) {
            $user->incrementFailedAttempts();

            if (!$user->isActive()) {
                throw ValidationException::withMessages([
                    'data.login' => 'Учётная запись заблокирована после 5 неверных попыток.',
                ]);
            }

            $remaining = 5 - $user->failed_attempts;

            throw ValidationException::withMessages([
                'data.login' => "Неверный логин или пароль. Осталось попыток: {$remaining}.",
            ]);
        }

        $user->resetFailedAttempts();

        auth('admin')->login($user);

        return app(LoginResponse::class);
    }
}
