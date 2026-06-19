<?php

namespace App\Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Blog\Models\Article;
use App\Modules\Blog\Models\Category;
use App\Modules\Blog\Models\Tag;
use App\Modules\System\Models\Setting;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    /**
     * Лента статей (с фильтром по категории/тегу через query-параметры).
     */
    public function index(string $locale): View
    {
        app()->setLocale($locale);

        $query = Article::query()->publishedFeed()->with(['category', 'tags']);

        if ($categorySlug = request('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($tagSlug = request('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $tagSlug));
        }

        return view('blog.feed', [
            'locale'     => $locale,
            'articles'   => $query->get(),
            'categories' => Category::query()->withCount('articles')->orderBy('name')->get(),
            'tags'       => Tag::query()->withCount('articles')->orderBy('name')->get(),
            'appName'    => Setting::get('site_name', config('app.name', 'Site')),
            'settings'   => Setting::allValues(),
        ]);
    }

    /**
     * Отдельная статья (/{locale}/blog/{slug}).
     */
    public function show(string $locale, string $slug): View
    {
        app()->setLocale($locale);

        $article = Article::query()
            ->publishedFeed()
            ->with(['category', 'tags'])
            ->where('slug', $slug)
            ->first();

        if (!$article) {
            throw new NotFoundHttpException();
        }

        return view('blog.article', [
            'locale'   => $locale,
            'article'  => $article,
            'appName'  => Setting::get('site_name', config('app.name', 'Site')),
            'settings' => Setting::allValues(),
        ]);
    }
}
