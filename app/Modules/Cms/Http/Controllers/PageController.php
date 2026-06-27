<?php

namespace App\Modules\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cms\Models\Page;
use App\Modules\Cms\Support\FrontCache;
use App\Modules\Cms\Support\TemplateRenderer;
use App\Modules\System\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Blade;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller
{
    /**
     * Главная страница лендинга (/{locale}).
     */
    public function home(string $locale): View|Response
    {
        app()->setLocale($locale);

        $page = Page::home();

        if (!$page) {
            throw new NotFoundHttpException();
        }

        return $this->render($page);
    }

    /**
     * Страница по иерархическому пути (/{locale}/about/team).
     */
    public function show(string $locale, string $path): View|Response
    {
        app()->setLocale($locale);

        $page = Page::findByPath($path);

        if (!$page) {
            throw new NotFoundHttpException();
        }

        // Главную отдаём только с "/{locale}", не дублируем по /{locale}/home
        if ($page->is_home) {
            throw new NotFoundHttpException();
        }

        return $this->render($page);
    }

    private function render(Page $page): View|Response
    {
        $locale = app()->getLocale();

        // Полностраничный HTML-кэш — только для «статичных» страниц (без CSRF/
        // old()/$errors/session в цепочке шаблона). Динамические (форма обратной
        // связи на главной) рендерим каждый раз, чтобы не заморозить токен/валидацию.
        if ($this->cacheable($page)) {
            // host+scheme в ключе: HTML содержит абсолютные URL (url()/asset()),
            // привязанные к хосту запроса. Без этого рендер под другим хостом
            // (CLI/tinker = localhost, второй домен, http/https) отравил бы кэш.
            $origin = request()->getSchemeAndHttpHost();

            $html = FrontCache::remember(
                "page.{$page->id}.{$locale}." . md5($origin),
                fn () => $this->html($page, $locale),
            );

            return response($html);
        }

        return $this->renderFresh($page, $locale);
    }

    /**
     * Можно ли отдавать готовый HTML страницы из кэша.
     */
    private function cacheable(Page $page): bool
    {
        if (!FrontCache::enabled()) {
            return false;
        }

        // Нет шаблона/тела — рендерится статичный fallback-view, кэшируем.
        if (!$page->template || blank($page->template->body)) {
            return true;
        }

        $slug = $page->template->slug;

        // Результат детектора зависит только от тел шаблонов — кэшируем его тоже.
        return !FrontCache::remember(
            "dyn.{$slug}",
            fn () => TemplateRenderer::chainIsDynamic($slug),
        );
    }

    /**
     * Свежий рендер страницы (без HTML-кэша).
     */
    private function renderFresh(Page $page, string $locale): View|Response
    {
        $data = $this->data($page, $locale);

        if ($page->template && filled($page->template->body)) {
            return response(Blade::render($page->template->body, $data));
        }

        return view('cms.page', $data);
    }

    /**
     * Готовый HTML страницы строкой (для записи в кэш).
     */
    private function html(Page $page, string $locale): string
    {
        $data = $this->data($page, $locale);

        if ($page->template && filled($page->template->body)) {
            return Blade::render($page->template->body, $data);
        }

        return view('cms.page', $data)->render();
    }

    /**
     * Переменные, доступные шаблону страницы.
     *
     * @return array<string, mixed>
     */
    private function data(Page $page, string $locale): array
    {
        return [
            'page'            => $page,
            'children'        => $page->children()->where('is_active', 1)->get(),
            'locale'          => $locale,
            'title'           => $page->tr('title'),
            'content'         => $page->tr('content'),
            'metaTitle'       => $page->tr('meta_title'),
            'metaDescription' => $page->tr('meta_description'),
            'metaKeywords'    => $page->tr('meta_keywords'),
            'appName'         => Setting::get('site_name', config('app.name', 'Site')),
            'settings'        => Setting::allValues(),
        ];
    }
}
