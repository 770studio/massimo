<?php

namespace App\Filament\Pages;

use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Laravel\Sanctum\PersonalAccessToken;
use Wallo\FilamentCompanies\FilamentCompanies;
use Wallo\FilamentCompanies\Pages\User\PersonalAccessTokens;

class CompanyAccessTokens extends PersonalAccessTokens
{

    public static function getSlug(): string
    {
        return 'api-access-tokens';
    }

    public function getTitle(): string
    {
        return __('API Access tokens');
    }

    /**
     * Get the current user of the application.
     */
    public function getUserProperty(): Authenticatable|null
    {
        return Auth::user();
    }

    protected function getTableQuery(): Builder
    {
        $auth = Filament::auth();


        return PersonalAccessToken::whereTokenableId(Auth::user()?->currentCompany?->getKey())
            ->whereTokenableType(FilamentCompanies::companyModel());
    }

    protected function getTableColumns(): array
    {
        return [
            Split::make([
                TextColumn::make('name')
                    ->label(__('filament-companies::default.labels.token_name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('abilities')
                    ->badge()
                    ->label(__('filament-companies::default.labels.permissions')),
            ]),
            Panel::make([
                Stack::make([
                    TextColumn::make('created_at')
                        ->label(__('filament-companies::default.labels.created_at'))
                        ->icon('heroicon-o-calendar-days')
                        ->formatStateUsing(static function ($state) {
                            return new HtmlString(
                                '<div>'
                                . __('filament-companies::default.descriptions.token_created_state', [
                                    'time_ago' => '<span class="font-bold text-sm text-primary-600 dark:text-primary-400">' . __($state->diffForHumans()) . '</span>',
                                    'user_name' => __(Auth::user()?->name),
                                ]) .
                                '</div>');
                        })
                        ->fontFamily('serif')
                        ->sortable(),
                    TextColumn::make('updated_at')
                        ->label(__('filament-companies::default.labels.updated_at'))
                        ->icon('heroicon-o-clock')
                        ->formatStateUsing(static function ($state) {
                            return __('filament-companies::default.descriptions.token_updated_state', ['time_ago' => $state->diffForHumans()]);
                        })
                        ->fontFamily('serif')
                        ->sortable(),
                    TextColumn::make('last_used_at')
                        ->label(__('filament-companies::default.labels.last_used_at'))
                        ->formatStateUsing(static function ($state) {
                            if ($state) {
                                return __('filament-companies::default.descriptions.token_last_used_state', ['time_ago' => $state->diffForHumans()]);
                            }

                            return __('filament-companies::default.descriptions.token_never_used');
                        })
                        ->fontFamily('serif')
                        ->sortable(),
                ])
            ])->collapsible(),
        ];
    }

    /**
     * @throws Exception
     */
    protected function getTableHeaderActions(): array
    {
        $permissions = FilamentCompanies::$permissions;
        $defaultPermissions = FilamentCompanies::$defaultPermissions;

        return [
            Tables\Actions\Action::make('create')
                ->label(__('filament-companies::default.buttons.create_token'))
                ->modalWidth(FilamentCompanies::getModals()['width'])
                ->action(function (array $data) use ($permissions) {
                    $name = $data['name'];
                    $abilities = array_values($data['abilities']);
                    $selected = array_intersect_key($permissions, array_flip($abilities));

                    $this->displayTokenValue(Auth::user()?->currentCompany?->createToken($name, FilamentCompanies::validPermissions($selected)));
                    $this->tokenCreatedNotification($name);
                })
                ->mountUsing(static function (Form $form) use ($permissions) {
                    $selected = array_intersect($permissions, FilamentCompanies::$defaultPermissions);
                    $form->fill([
                        'abilities' => array_keys($selected),
                    ]);
                })
                ->form([
                    TextInput::make('name')
                        ->label(__('filament-companies::default.labels.token_name'))
                        ->required(),
                    CheckboxList::make('abilities')
                        ->label(__('filament-companies::default.labels.permissions'))
                        ->required()
                        ->options($permissions)
                        ->columns()
                        ->default($defaultPermissions),
                ]),
        ];
    }
}
