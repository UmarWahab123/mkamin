<x-filament-panels::page>
    @if($this->record)
        <x-filament::tabs>
            <x-filament::tabs.item
                :active="$activeTab === 'profile'"
                wire:click="$set('activeTab', 'profile')"
            >
                {{ __('Profile') }}
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$activeTab === 'bookings'"
                wire:click="$set('activeTab', 'bookings')"
            >
                {{ __('Bookings') }}
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$activeTab === 'working-hours'"
                wire:click="$set('activeTab', 'working-hours')"
            >
                {{ __('Working Hours') }}
            </x-filament::tabs.item>
        </x-filament::tabs>

        @if($activeTab === 'profile')
            <form wire:submit="save">
                {{ $this->form }}

                <div class="mt-4">
                    <x-filament::button
                        type="submit"
                        wire:loading.attr="disabled"
                    >
                        {{ __('Save') }}
                    </x-filament::button>
                </div>
            </form>
        @elseif($activeTab === 'bookings')
            @livewire(App\Filament\Resources\StaffResource\RelationManagers\BookingsRelationManager::class, ['ownerRecord' => $this->record, 'pageClass' => $this->getPageClass()])
        @elseif($activeTab === 'working-hours')
            @livewire(App\Filament\Resources\StaffResource\RelationManagers\TimeIntervalsRelationManager::class, ['ownerRecord' => $this->record, 'pageClass' => $this->getPageClass()])
        @endif
    @else
        <div class="p-4">
            <x-filament-notifications::notification
                color="danger"
                icon="heroicon-o-exclamation-circle"
            >
                {{ __('No staff profile found.') }}
            </x-filament-notifications::notification>
        </div>
    @endif
</x-filament-panels::page>
