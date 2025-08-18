<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Component;
use Illuminate\Contracts\View\View;

class LivewireComponent extends Component
{
    protected string $view = 'filament.forms.components.livewire-component';

    protected string $livewireComponent;
    protected array $livewireParams = [];

    public static function make(string $livewireComponent, array $params = []): static
    {
        $static = app(static::class);
        $static->livewireComponent = $livewireComponent;
        $static->livewireParams = $params;

        return $static;
    }

    public function getLivewireComponent(): string
    {
        return $this->livewireComponent;
    }

    public function getLivewireParams(): array
    {
        return $this->livewireParams;
    }

    public function getView(): string
    {
        return $this->view;
    }
}