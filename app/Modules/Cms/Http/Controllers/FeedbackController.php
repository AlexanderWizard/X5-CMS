<?php

namespace App\Modules\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cms\Models\Feedback;
use App\Modules\System\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Приём формы обратной связи с публичного сайта.
 * Сохраняет заявку в таблицу cms_feedback (с IP отправителя) — она видна
 * в админке (раздел CMS → «Обратная связь»).
 *
 * Управляется настройками сайта:
 *  - feedback_enabled        — форма включена/выключена;
 *  - feedback_limit_per_hour — лимит заявок в час со всего сайта (0 = без лимита);
 *  - feedback_limit_per_ip   — лимит заявок в час с одного IP (0 = без лимита).
 */
class FeedbackController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // Форма отключена в настройках — отдаём 404 (как будто маршрута нет).
        if (! Setting::bool('feedback_enabled', true)) {
            throw new NotFoundHttpException();
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:191',
            'email'   => 'required|email|max:191',
            'message' => 'required|string|max:5000',
        ]);

        $ip = $request->ip();

        if ($error = $this->rateLimitError($ip)) {
            return back()
                ->withInput()
                ->withErrors(['message' => $error])
                ->withFragment('feedback');
        }

        Feedback::create($validated + ['ip_address' => $ip]);

        return back()
            ->with('feedback_success', true)
            ->withFragment('feedback');
    }

    /**
     * Проверка часовых лимитов. Возвращает текст ошибки или null, если всё ок.
     */
    private function rateLimitError(?string $ip): ?string
    {
        $perHour = (int) Setting::get('feedback_limit_per_hour', 0);
        $perIp   = (int) Setting::get('feedback_limit_per_ip', 0);

        $since = now()->subHour();

        if ($perHour > 0 && Feedback::where('created_at', '>=', $since)->count() >= $perHour) {
            return 'Слишком много обращений. Пожалуйста, попробуйте позже.';
        }

        if ($perIp > 0 && $ip !== null
            && Feedback::where('ip_address', $ip)->where('created_at', '>=', $since)->count() >= $perIp) {
            return 'Вы отправили слишком много сообщений. Попробуйте позже.';
        }

        return null;
    }
}
