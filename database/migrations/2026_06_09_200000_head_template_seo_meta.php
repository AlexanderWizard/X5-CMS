<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('templates')->where('slug', 'head')->update([
            'body' => $this->headBody(),
        ]);
    }

    public function down(): void
    {
        DB::table('templates')->where('slug', 'head')->update([
            'body' => $this->oldHeadBody(),
        ]);
    }

    private function headBody(): string
    {
        return <<<'BLADE'
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ($page->meta_title ?? null) ? $page->meta_title : $title }} — {{ $appName }}</title>
    @if (!empty($page->meta_description))
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
    @if (!empty($page->meta_keywords))
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
</head>
BLADE;
    }

    private function oldHeadBody(): string
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
