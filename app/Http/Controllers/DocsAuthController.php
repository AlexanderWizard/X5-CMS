<?php

namespace App\Http\Controllers;

use App\Models\DocsUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DocsAuthController extends Controller
{
    public function showLogin(): View
    {
        return view('docs.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $user = DocsUser::where('login', $request->input('login'))->first();

        // Пользователь не найден — не раскрываем детали
        if (!$user) {
            return back()->withErrors(['login' => 'Неверный логин или пароль.']);
        }

        // Учётка заблокирована
        if (!$user->isActive()) {
            return back()->withErrors(['login' => 'Учётная запись заблокирована. Обратитесь к администратору.']);
        }

        // Неверный пароль
        if (!Hash::check($request->input('password'), $user->password)) {
            $user->incrementFailedAttempts();

            $remaining = 5 - $user->failed_attempts;

            if (!$user->isActive()) {
                return back()->withErrors(['login' => 'Учётная запись заблокирована после 5 неверных попыток.']);
            }

            return back()->withErrors([
                'login' => "Неверный логин или пароль. Осталось попыток: {$remaining}.",
            ]);
        }

        // Успешный вход — сбрасываем счётчик
        $user->resetFailedAttempts();
        $request->session()->put('docs_authenticated', true);

        return redirect()->intended('/docs');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('docs_authenticated');
        return redirect()->route('docs.login');
    }
}
