<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Системный шаблон head
        if (!DB::table('templates')->where('slug', 'head')->exists()) {
            DB::table('templates')->insert([
                'name'      => 'Head (мета и стили)',
                'slug'      => 'head',
                'is_system' => 1,
                'body'      => $this->headBody(),
            ]);
        }

        // В шаблоне home заменяем встроенный <head>...</head> на @partial('head')
        $home = DB::table('templates')->where('slug', 'home')->first();

        if ($home) {
            $body = preg_replace('/<head>.*?<\/head>/s', "@partial('head')", $home->body, 1);
            DB::table('templates')->where('id', $home->id)->update(['body' => $body]);
        }
    }

    public function down(): void
    {
        DB::table('templates')->where('slug', 'head')->delete();
    }

    private function headBody(): string
    {
        return <<<'BLADE'
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — {{ $appName }}</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
</head>
BLADE;
    }
};
