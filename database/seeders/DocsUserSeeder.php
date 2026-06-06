<?php

namespace Database\Seeders;

use App\Models\DocsUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DocsUserSeeder extends Seeder
{
    public function run(): void
    {
        DocsUser::updateOrCreate(
            ['login' => 'user'],
            ['password' => Hash::make('user')]
        );
    }
}
