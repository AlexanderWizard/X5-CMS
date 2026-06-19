<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Добавляет в шаблон главной секцию формы обратной связи.
 * Форма постит на cms.feedback (POST /feedback), сообщение падает в
 * messages_queue с channel=feedback и видно в админке.
 *
 * Секция вставляется перед финальной CTA-полосой (<section class="cta">).
 */
return new class extends Migration
{
    private const MARKER = '    <section class="cta">';

    public function up(): void
    {
        $section = <<<'HTML'
    <section class="feedback" id="feedback">
        <div class="wrap">
            <div class="sec-head">
                <div class="eyebrow">Контакты</div>
                <h2>Обратная связь</h2>
                <p>Напишите нам — сообщение попадёт в очередь и будет обработано.</p>
            </div>

            @if (session('feedback_success'))
                <div class="fb-alert fb-ok">Спасибо! Ваше сообщение отправлено.</div>
            @endif

            @if ($errors->any())
                <div class="fb-alert fb-err">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form class="fb-form" method="POST" action="{{ route('cms.feedback') }}">
                @csrf
                <div class="fb-row">
                    <input type="text" name="name" placeholder="Ваше имя" value="{{ old('name') }}" maxlength="191" required>
                    <input type="email" name="email" placeholder="E-mail" value="{{ old('email') }}" maxlength="191" required>
                </div>
                <textarea name="message" placeholder="Сообщение" rows="5" maxlength="5000" required>{{ old('message') }}</textarea>
                <button type="submit" class="btn btn-primary">Отправить →</button>
            </form>
        </div>
    </section>

HTML;

        $body = DB::table('templates')->where('slug', 'home')->value('body');

        if ($body === null || str_contains($body, 'class="feedback"')) {
            return;
        }

        $body = str_replace(self::MARKER, $section . self::MARKER, $body);

        DB::table('templates')->where('slug', 'home')->update(['body' => $body]);
    }

    public function down(): void
    {
        // Контент шаблона редактируется в админке — откат не предусмотрен.
    }
};
