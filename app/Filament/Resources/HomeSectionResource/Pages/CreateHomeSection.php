<?php

namespace App\Filament\Resources\HomeSectionResource\Pages;

use App\Filament\Resources\HomeSectionResource;
use App\Models\HomeSection;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateHomeSection extends CreateRecord
{
    protected static string $resource = HomeSectionResource::class;

    private bool $wasUpdated = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Override record creation to update if section_name exists
     */
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Ensure content is properly handled
        if (isset($data['content']) && is_array($data['content'])) {
            $data['content'] = $this->cleanContentArray($data['content']);
        }

        if (empty($data['order'])) {
            $data['order'] = static::getResource()::getModel()::max('order') + 1 ?? 1;
        }

        if (!isset($data['visible'])) {
            $data['visible'] = true;
        }

        if (auth()->check()) {
            $data['created_by'] = auth()->id();
        }

        // If exists, update instead of creating a duplicate
        $existing = HomeSection::where('section_name', $data['section_name'])->first();
        if ($existing) {
            $existing->update($data);
            $this->wasUpdated = true;

            Notification::make()
                ->title('Home Section Updated')
                ->success()
                ->body('The home section was updated successfully.')
                ->send();

            return $existing;
        }

        // Otherwise create a new record
        return HomeSection::create($data);
    }

    private function cleanContentArray(array $content): array
    {
        $fileFields = [
            'bgImage', 'image', 'image1', 'image2', 'image3',
            'background_image', 'hero_image', 'gallery_images',
            'profile_image', 'slide_image'
        ];

        return array_filter($content, function ($value, $key) use ($fileFields) {
            if (in_array($key, $fileFields)) {
                return true;
            }

            if (is_array($value)) {
                $cleaned = $this->cleanContentArray($value);
                return !empty($cleaned);
            }

            return $value !== null && $value !== '';
        }, ARRAY_FILTER_USE_BOTH);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    /**
     * Override default created notification to avoid duplicates
     */
    protected function getCreatedNotification(): ?Notification
    {
        if ($this->wasUpdated) {
            return null; // Skip creation notification if we just updated
        }

        return Notification::make()
            ->success()
            ->title('Home Section Created')
            ->body('The home section has been created successfully.')
            ->duration(5000);
    }
}