<?php

namespace App\Filament\Traits;

trait HasCloseAndRedirect
{
    public bool $closeAfterCreating = false;
    public ?string $redirectUrl = null;

    public function mount($record = null): void
    {
        if ($record !== null) {
            parent::mount($record);
        } else {
            parent::mount();
        }

        // Store the parameters in the component state
        $this->closeAfterCreating = request()->boolean('closeAfterCreating');
        $this->redirectUrl = request()->get('redirect');
    }

    protected function getRedirectUrl(): string
    {
        if ($this->closeAfterCreating) {
            return 'javascript:window.close();';
        }

        if ($this->redirectUrl) {
            return $this->redirectUrl;
        }

        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        if ($this->closeAfterCreating) {
            return null;
        }

        return parent::getCreatedNotificationTitle();
    }
}
