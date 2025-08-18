<?php

namespace Filawidget\Resources\WidgetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filawidget\Models\WidgetField;
use Filawidget\Models\WidgetType;
use Filawidget\Resources\WidgetResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class EditWidget extends EditRecord
{
    protected static string $resource = WidgetResource::class;

    // Override the mount method to set up fieldsIds and values
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Get the widget type
        if (isset($data['widget_type_id'])) {
            $widgetType = WidgetType::find($data['widget_type_id']);
            if ($widgetType) {
                // Set the fieldsIds from the widget type
                $data['fieldsIds'] = $widgetType->fieldsIds;

                // Get the widget ID
                $widgetId = $this->record->id;

                // Fetch field types to understand how to handle each value
                $fieldTypes = [];
                if (!empty($widgetType->fieldsIds)) {
                    $fields = \Filawidget\Models\Field::whereIn('id', $widgetType->fieldsIds)
                        ->get(['id', 'type'])
                        ->keyBy('id');

                    foreach ($fields as $field) {
                        $fieldTypes[$field->id] = $field->type;
                    }
                }

                // Fetch all saved field values for this widget
                $savedValues = WidgetField::where('widget_id', $widgetId)
                    ->whereIn('widget_field_id', $widgetType->fieldsIds)
                    ->get(['widget_field_id', 'value'])
                    ->keyBy('widget_field_id');

                // Create a repeater item with field_ID keys for all saved values
                $repeaterItem = [];
                foreach ($widgetType->fieldsIds as $fieldId) {
                    $fieldKey = 'field_' . $fieldId;

                    // Get the value and field type
                    $savedField = $savedValues->get($fieldId);
                    $value = $savedField ? $savedField->value : '';
                    $fieldType = $fieldTypes[$fieldId] ?? 'text';

                    // Format image and file fields correctly for Filament
                    if (in_array($fieldType, ['image', 'file']) && !empty($value)) {
                        // Check if it's a JSON string
                        if (is_string($value) && (str_starts_with($value, '[') || str_starts_with($value, '{'))) {
                            $decodedValue = json_decode($value, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                // Successfully decoded JSON
                                if (is_array($decodedValue)) {
                                    // Array of files
                                    $value = $decodedValue;
                                }
                            }
                        } else {
                            // Single file path - convert to array format for Filament
                            $value = [$value];
                        }
                    }

                    $repeaterItem[$fieldKey] = $value;
                }

                // Log what we're setting for debugging
                Log::info("Setting repeater values for edit form", ['repeaterItem' => $repeaterItem]);

                // Set the values with a unique key for the repeater item
                $data['values'] = [Str::uuid()->toString() => $repeaterItem];
            }
        }

        // If we didn't set values above, initialize with an empty array
        if (!isset($data['values'])) {
            $data['values'] = ['item' => []];
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Generate a slug from the name field
        $data['slug'] = Str::slug($data['name']);

        // Remove fieldsIds from data as it's not a database column
        if (isset($data['fieldsIds'])) {
            unset($data['fieldsIds']);
        }

        // Remove values as they will be handled separately
        if (isset($data['values'])) {
            unset($data['values']);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $widgetId = $this->record->id;
        $data = $this->form->getRawState();
        $fieldsIds = $data['fieldsIds'] ?? [];

        // Get field types
        $fieldTypes = [];
        if (!empty($fieldsIds)) {
            $fields = \Filawidget\Models\Field::whereIn('id', $fieldsIds)
                ->get(['id', 'type'])
                ->keyBy('id');

            foreach ($fields as $field) {
                $fieldTypes[$field->id] = $field->type;
            }
        }

        // Extract values from repeater
        $values = [];
        if (isset($data['values']) && is_array($data['values'])) {
            $repeaterItem = reset($data['values']);
            if (is_array($repeaterItem)) {
                foreach ($repeaterItem as $key => $value) {
                    if (preg_match('/^field_(\d+)$/', $key, $matches)) {
                        $fieldId = (int)$matches[1];
                        $values[$fieldId] = $value;
                    }
                }
            }
        }

        // Delete existing widget fields
        WidgetField::where('widget_id', $widgetId)->delete();

        // Save new values
        if (!empty($values)) {
            foreach ($values as $fieldId => $value) {
                $fieldType = $fieldTypes[$fieldId] ?? 'text';

                // Process value based on field type
                if (in_array($fieldType, ['file', 'image']) && is_array($value)) {
                    // Safer handling of file upload arrays
                    if (!empty($value)) {
                        // Check if it's an associative array (like what Filament returns)
                        if (isset($value['name']) || isset($value['path'])) {
                            // It's a single file in associative array format
                            $value = json_encode($value);
                        }
                        // Check if it's a numerically indexed array
                        else if (isset($value[0])) {
                            // If there's just one file, use it directly
                            if (count($value) === 1) {
                                // Check if the first item is a string (path) or an array (file data)
                                if (is_string($value[0])) {
                                    $value = $value[0];
                                } else {
                                    $value = json_encode($value);
                                }
                            } else {
                                // Multiple files
                                $value = json_encode($value);
                            }
                        } else {
                            // Some other type of array, just encode it
                            $value = json_encode($value);
                        }
                    } else {
                        // Empty array, set to empty string
                        $value = '';
                    }
                } elseif (is_array($value)) {
                    $value = json_encode($value);
                }

                // Create the widget field
                WidgetField::create([
                    'widget_id' => $widgetId,
                    'widget_field_id' => $fieldId,
                    'value' => $value,
                ]);
            }
        }
    }
}
