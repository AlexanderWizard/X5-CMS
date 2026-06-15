<?php

namespace App\Modules\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cms\Models\Page;
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
        $children = $page->children()->where('is_active', 1)->get();

        $data = [
            'page'            => $page,
            'children'        => $children,
            'locale'          => app()->getLocale(),
            'title'           => $page->tr('title'),
            'content'         => $page->tr('content'),
            'metaTitle'       => $page->tr('meta_title'),
            'metaDescription' => $page->tr('meta_description'),
            'metaKeywords'    => $page->tr('meta_keywords'),
            'appName'         => Setting::get('site_name', config('app.name', 'Site')),
            'settings'        => Setting::allValues(),
        ];

        // Если у страницы есть шаблон с телом — рендерим его Blade-разметку.
        if ($page->template && filled($page->template->body)) {
            $html = Blade::render($page->template->body, $data);

            return response($html);
        }

        // Иначе — встроенный шаблон по умолчанию.
        return view('cms.page', $data);
    }
}
