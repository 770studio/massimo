<?php

namespace App\Providers\Filament;

use App\Models\Company;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Wallo\FilamentCompanies\Pages\User\PersonalAccessTokens;
use Wallo\FilamentCompanies\Pages\User\Profile;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {


        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => '#c61044',
            ])
            ->darkModeBrandLogo(asset('images/divbyzero_dark_logo.svg'))
            ->brandLogo(asset('images/divbyzero_light_logo.svg'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->tenantMenu(fn(): bool => (count(auth()->user()->getTenants($panel)) > 1))
            ->tenant(Company::class)
            ->pages([
                Pages\Dashboard::class,
                Profile::class,
                PersonalAccessTokens::class,
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('Profile')
                    ->icon('heroicon-o-user-circle')
                    ->url(static fn() => url(Profile::getUrl())),
                MenuItem::make()
                    ->label('Company')
                    ->icon('heroicon-o-building-office')
                    ->url(static fn() => url(Pages\Dashboard::getUrl(panel: 'company', tenant: Auth::user()->personalCompany()))),
            ])
            ->navigationItems([
                NavigationItem::make('Personal Access Tokens')
                    ->label(static fn(): string => __('filament-companies::default.navigation.links.tokens'))
                    ->icon('heroicon-o-key')
                    ->url(static fn() => url(PersonalAccessTokens::getUrl())),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
