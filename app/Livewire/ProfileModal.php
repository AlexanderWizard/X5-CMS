<?php

namespace App\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Управление профилем (язык интерфейса + часовой пояс) во всплывающем модальном окне.
 *
 * Компонент рендерится глобально (BODY_END) и держит экшен `editProfile`.
 * Пункт «Профиль» в меню пользователя диспатчит событие open-profile-modal,
 * по которому экшен монтируется и открывается модалка. После сохранения —
 * редирект на текущую страницу, чтобы применились новая локаль/таймзона
 * (они выставляются на старте запроса middleware/AppServiceProvider).
 */
class ProfileModal extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    /** URL страницы, с которой открыли модалку (для редиректа после сохранения). */
    public ?string $returnUrl = null;

    public function editProfileAction(): Action
    {
        return Action::make('editProfile')
            ->label(__('admin.profile.title'))
            ->modalHeading(__('admin.profile.title'))
            ->icon('heroicon-o-user-circle')
            ->modalWidth(Width::Large)
            ->fillForm(function (): array {
                $user = auth('admin')->user();

                return [
                    'locale'   => $user?->locale,
                    'timezone' => $user?->timezone,
                ];
            })
            ->schema([
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
            ])
            ->action(function (array $data): void {
                $user = auth('admin')->user();
                $user->locale   = $data['locale'];
                $user->timezone = $data['timezone'];
                $user->save();

                Notification::make()
                    ->success()
                    ->title(__('admin.profile.saved'))
                    ->send();

                // Перезагружаем страницу — чтобы применились локаль и таймзона.
                $this->redirect($this->returnUrl ?: filament()->getHomeUrl());
            });
    }

    #[On('open-profile-modal')]
    public function open(?string $returnUrl = null): void
    {
        $this->returnUrl = $returnUrl;
        $this->mountAction('editProfile');
    }

    /**
     * Опции часовых поясов: "(UTC+03:00) Europe/Moscow".
     *
     * @return array<string, string>
     */
    private function getTimezoneOptions(): array
    {
        $options = [];

        foreach (timezone_identifiers_list() as $tz) {
            try {
                $offset = (new \DateTimeZone($tz))->getOffset(new \DateTime('now', new \DateTimeZone('UTC')));
                $hours  = intdiv(abs($offset), 3600);
                $mins   = abs($offset % 3600) / 60;
                $sign   = $offset >= 0 ? '+' : '-';
                $options[$tz] = sprintf('(UTC%s%02d:%02d) %s', $sign, $hours, $mins, str_replace('_', ' ', $tz));
            } catch (\Exception) {
                $options[$tz] = $tz;
            }
        }

        return $options;
    }

    public function render(): View
    {
        return view('livewire.profile-modal');
    }
}
