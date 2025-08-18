<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class LeafletMap extends Field
{
    protected string $view = 'forms.components.leaflet-map';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function defaultLocation(?array $latLng): static
    {
        return $this->extraAttributes(['data-default-location' => json_encode($latLng)]);
    }

    public function defaultZoom(int $zoom): static
    {
        return $this->extraAttributes(['data-default-zoom' => $zoom]);
    }
}
