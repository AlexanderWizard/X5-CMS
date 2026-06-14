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
     * Главная страница лендинга.
     */
    public function home(): View|Response
    {
        $page = Page::home();

        if (!$page) {
            throw new NotFoundHttpException();
        }

        return $this->render($page);
    }

    /**
     * Страница по иерархическому пути (about/team).
     */
    public function show(string $path): View|Response
    {
        $page = Page::findByPath($path);

        if (!$page) {
            throw new NotFoundHttpException();
        }

        // Главную отдаём только с "/", не дублируем по /home
        if ($page->is_home) {
            throw new NotFoundHttpException();
        }

        return $this->render($page);
    }

    private function render(Page $page): View|Response
    {
        $children = $page->children()->where('is_active', 1)->get();

        $data = [
            'page'     => $page,
            'children' => $children,
            'title'    => $page->title,
            'content'  => $page->content,
            'appName'  => Setting::get('site_name', config('app.name', 'Site')),
            'settings' => Setting::allValues(),
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
