<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Actions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

class Dashboard extends BaseDashboard
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('quickReservation')
                ->label('Quick Reservation')
                ->icon('heroicon-o-calendar')
                ->color('primary')
                ->url(route('filament.admin.resources.booked-reservations.quick-create'))
                ->button()
                ->visible(function () {
                    if (auth()->check()) {
                        $user = User::find(auth()->id());
                        if ($user) {
                            return $user->isAdmin() || $user->isPointOfSale();
                        }
                    }
                    return false;
                }),
            Actions\Action::make('clearCache')
                ->label('Clear Cache')
                ->color('warning')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    // Clear Laravel's cache
                    Artisan::call('cache:clear');
                    Artisan::call('config:clear');
                    Artisan::call('view:clear');
                    Artisan::call('route:clear');

                    // Clear the application cache
                    Cache::flush();

                    // Clear configuration cache
                    if (function_exists('opcache_reset')) {
                        opcache_reset();
                    }

                    // Show notification using Filament's notification API
                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Cache cleared successfully!')
                        ->send();
                }),
            // Actions\DeleteAction::make(),
        ];
    }
}
