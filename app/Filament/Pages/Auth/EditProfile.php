<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('locale')
                ->label(__('admin.profile.locale'))
                ->options([
                    'ru' => '🇷🇺 Русский',
                    'en' => '🇬🇧 English',
                ])
                ->required()
                ->native(false),
        ]);
    }
}
