<?php

namespace App\Filament\Resources\AboutSectionResource\Pages;

use App\Filament\Resources\AboutSectionResource;
use Filament\Resources\Pages\EditRecord;

class EditAboutSection extends EditRecord
{
    protected static string $resource = AboutSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Add any header actions you need here
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ensure content is properly decoded if it's stored as JSON string
        if (isset($data['content']) && is_string($data['content'])) {
            $data['content'] = json_decode($data['content'], true) ?? [];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure content is properly handled for saving
        if (isset($data['content']) && is_array($data['content'])) {
            // Clean up any empty values in the content array, but preserve file data
            $data['content'] = $this->cleanContentArray($data['content']);
        }

        return $data;
    }

    private function cleanContentArray(array $content): array
    {
        // Remove null and empty string values, but keep 0, false, and uploaded files
        return array_filter($content, function ($value, $key) {
            // Always preserve image/file fields even if they appear empty
            $fileFields = [
                'background_image', 'left_image', 'right_image', 'features_image', 
                'image', 'image_1', 'image_2', 'image_3', 'background'
            ];
            
            if (in_array($key, $fileFields)) {
                return true; // Keep all file fields
            }
            
            if (is_array($value)) {
                $cleaned = $this->cleanContentArray($value);
                return !empty($cleaned);
            }
            
            return $value !== null && $value !== '';
        }, ARRAY_FILTER_USE_BOTH);
    }
}