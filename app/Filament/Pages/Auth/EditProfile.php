<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public function mount(): void
    {
        parent::mount();

        // Запоминаем откуда пришли (только если это не сама страница профиля)
        $previous = url()->previous();
        if ($previous && !str_contains($previous, '/admin/profile')) {
            session()->put('profile_back_url', $previous);
        }
    }

    protected function getRedirectUrl(): ?string
    {
        return session()->pull('profile_back_url', filament()->getHomeUrl());
    }

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

            Select::make('timezone')
                ->label(__('admin.profile.timezone'))
                ->options($this->getTimezoneOptions())
                ->required()
                ->searchable()
                ->native(false),
        ]);
    }

    private function getTimezoneOptions(): array
    {
        $options = [];

        foreach (timezone_identifiers_list() as $tz) {
            try {
                $offset = (new \DateTimeZone($tz))->getOffset(new \DateTime('now', new \DateTimeZone('UTC')));
                $hours  = intdiv(abs($offset), 3600);
                $mins   = abs($offset % 3600) / 60;
                $sign   = $offset >= 0 ? '+' : '-';
                $label  = sprintf('(UTC%s%02d:%02d) %s', $sign, $hours, $mins, str_replace('_', ' ', $tz));
                $options[$tz] = $label;
            } catch (\Exception) {
                $options[$tz] = $tz;
            }
        }

        return $options;
    }
}
